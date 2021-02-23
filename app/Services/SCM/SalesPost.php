<?php


namespace App\Services\SCM;


use App\Models\SCM\SalesHeader;
use App\Models\SCM\SalesLine;
use Illuminate\Database\Eloquent\Collection;
use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesPost
{
    /**
     * @param Collection|SalesHeader $salesHeaders
     *
     */
    public static function post($salesHeaders)
    {
        /**
         * @var Collection|SalesHeader $salesHeaders
         */
        if(!$salesHeaders instanceof Collection){
            $salesHeaders = collect($salesHeaders);
        }

        DB::transaction(function() use ($salesHeaders){
            $carbon = Carbon::now();

            $salesHeaders->each(function(SalesHeader $salesHeader) use ($carbon) {
                $salesHeader->open_sales_lines()->each(function(Salesline $salesLine) use ($salesHeader, $carbon){
                    //1. Lagermenge prüfen
                    $applyableEntries = $salesLine->item_variant->open_storage_entries()->where([
                        ['source_doc_type', '=', 'Purchase'],
                        ['storage_id', '=', $salesHeader->storage_id],
                        ['posting_date', '<=', $salesHeader->posting_date],
                    ])->orderBy('created_at', $salesLine->item->storage_posting_method = 'FIFO' ? 'asc' : 'desc');
                    $postableQuantity = $salesLine->quantity;
                    $pageIndex = 1;

                    while($postableQuantity > 0) {
                        $page = $applyableEntries->forPage($pageIndex, 1);

                        if($page->count() === 0) {
                            throw new \RuntimeException('Unzureichender Lagerbestand (Keine weiteren Posten)');
                        }

                        /**
                         * @var $applyToEntry StorageEntry
                         */
                        $applyToEntry = $page->get()[0];

                        if($applyToEntry->remaining_quantity < 1) {
                            throw new \RuntimeException('Unzureichender Lagerbestand (Restmenge kleiner als 1)');
                        }

                        $postQuantity = ($applyToEntry->remaining_quantity > $postableQuantity) ? $postableQuantity : $applyToEntry->remaining_quantity;

                        //2. Lagerposten erstellen
                        $storageEntry = new StorageEntry();
                        $storageEntry->storage_id = $salesHeader->storage_id;
                        $storageEntry->item_id = $salesLine->item_id;
                        $storageEntry->item_variant_id = $salesLine->item_variant_id;
                        $storageEntry->item_description = $salesLine->item->description;
                        $storageEntry->item_variant_description = $salesLine->description;
                        $storageEntry->source_doc_type = "Sales";
                        $storageEntry->source_doc_id = $salesHeader->id;
                        $storageEntry->source_doc_line_no = $salesLine->line_no;
                        $storageEntry->user_id = $salesLine->user_id;
                        $storageEntry->employee_id = $salesHeader->employee_id;
                        $storageEntry->posting_date = $salesHeader->posting_date;
                        $storageEntry->quantity = $postQuantity * -1; // Wird negiert
                        $storageEntry->applies_to_entry = $applyToEntry->entry_no;
                        $storageEntry->remaining_quantity = 0;
                        $storageEntry->delivery_date = null;
                        $storageEntry->canceled_at = null;
                        $storageEntry->closed_at = $carbon;

                        //3. Bebuchten Lagerposten aktualisieren
                        $applyToEntry->remaining_quantity -= $postQuantity;
                        if($applyToEntry->remaining_quantity < 1) {
                            $applyToEntry->closed_at = $carbon;
                            $pageIndex--;
                        }

                        //4. Lagerposten erfassen
                        $storageEntry->save();
                        $applyToEntry->save();

                        $pageIndex++;
                        $postableQuantity -= $postQuantity;
                    }

                    //5. Wertposten erstellen
                    $valueEntry = new ValueEntry();
                    $valueEntry->outlet_id = $salesHeader->outlet_id;
                    $valueEntry->item_id = $salesLine->item_id;
                    $valueEntry->item_variant_id = $salesLine->item_variant_id;
                    $valueEntry->item_description = $salesLine->item->description;
                    $valueEntry->item_variant_description = $salesLine->description;
                    $valueEntry->source_doc_type = "Sales";
                    $valueEntry->source_doc_id = $salesHeader->id;
                    $valueEntry->source_doc_line_no = $salesLine->line_no;
                    $valueEntry->user_id = $salesLine->user_id;
                    $valueEntry->employee_id = $salesHeader->employee_id;
                    $valueEntry->posting_date = $salesHeader->posting_date;
                    $valueEntry->unit_price = $salesLine->unit_price;
                    $valueEntry->vat_percent = $salesLine->vat_percent;
                    $valueEntry->vat_amount = $salesLine->vat_amount;
                    $valueEntry->line_amount = $salesLine->line_amount;
                    $valueEntry->vendor_id = null;
                    $valueEntry->canceled_at = null;

                    //6. Wertposten erfassen
                    $valueEntry->save();

                    $salesLine->archived_at = $carbon;
                    $salesLine->save();
                });

                if($salesHeader->open_sales_lines()->count() < 1) {
                    $salesHeader->archived_at = $carbon;
                    $salesHeader->save();
                }
            });
        });
    }
}

<?php


namespace App\Services\SCM;


use App\Models\SCM\SalesHeader;
use App\Models\SCM\SalesLine;
use Illuminate\Database\Eloquent\Collection;
use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;

class SalesPost
{
    /**
     * @param Collection|SalesHeader $salesHeaders
     *
     */
    public function post($salesHeaders)
    {
        /**
         * @var Collection|SalesHeader $salesHeaders
         */
        if(!$salesHeaders instanceof Collection){
            $salesHeaders = collect($salesHeaders);
        }

        DB::transaction(function() use ($salesHeaders){
            $salesHeaders->each(function(SalesHeader $salesHeader) {
                sales_lines()->each(function(Salesline $salesLine) use ($salesHeader){
                    //1. Lagermenge prüfen

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
                    $storageEntry->quantity = $salesLine->quantity * -1; // Wird negiert
                    $storageEntry->applies_to_entry = null; // TODO:
                    $storageEntry->remaining_quantity = 0; // TODO:
                    $storageEntry->delivery_date = null; // TODO:
                    $storageEntry->canceled_at = null; // TODO:
                    $storageEntry->closed_at = null; // TODO:

                    //3. Wertposten erstellen
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
                    $valueEntry->applies_to_entry = null; // TODO;
                    $valueEntry->vendor_id = null; // TODO
                    $valueEntry->canceled_at = null; // TODO
                    $valueEntry->closed_at = null; // TODO

                    //4. Posten erfassen
                    $storageEntry->save();
                    $valueEntry->save();
                });
            });
        });
    }
}

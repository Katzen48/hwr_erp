<?php


namespace App\Services\SCM;

use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\PurchaseLine;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PurchasePost
{
    /**
     * @param Collection|PurchaseHeader $purchaseHeaders
     *
     */
    public static function post($purchaseHeaders)
    {
        /**
         * @var Collection|PurchaseHeader $collection
         */
        $collection = collect();
        if(!$purchaseHeaders instanceof Collection){
            $collection->push($purchaseHeaders);
        } else {
            $collection = $purchaseHeaders;
        }

        DB::transaction(function() use ($collection) {
            $carbon = Carbon::now();

            $collection->each(function(PurchaseHeader $purchaseHeader) use ($carbon) {
                $purchaseHeader->open_purchase_lines()->each(function(PurchaseLine $purchaseLine) use ($purchaseHeader, $carbon) {
                    //1. Lagerposten erstellen
                    $storageEntry = new StorageEntry();
                    $storageEntry->storage_id = $purchaseHeader->storage_id;
                    $storageEntry->item_id = $purchaseLine->item_id;
                    $storageEntry->item_variant_id = $purchaseLine->item_variant_id;
                    $storageEntry->item_description = $purchaseLine->item->description;
                    $storageEntry->item_variant_description = $purchaseLine->description;
                    $storageEntry->source_doc_type = "Purchase";
                    $storageEntry->source_doc_id = $purchaseHeader->id;
                    $storageEntry->source_doc_line_no = $purchaseLine->line_no;
                    $storageEntry->user_id = $purchaseLine->user_id;
                    $storageEntry->employee_id = $purchaseHeader->employee_id;
                    $storageEntry->posting_date = $purchaseHeader->posting_date;
                    $storageEntry->quantity = $purchaseLine->quantity;
                    $storageEntry->applies_to_entry = null;
                    $storageEntry->remaining_quantity = $purchaseLine->quantity;
                    $storageEntry->delivery_date = $purchaseHeader->delivery_date;
                    $storageEntry->canceled_at = null;
                    $storageEntry->closed_at = null;

                    // 2. Wertposten erstellen
                    $valueEntry = new ValueEntry();
                    $valueEntry->outlet_id = $purchaseHeader->outlet_id;
                    $valueEntry->item_id = $purchaseLine->item_id;
                    $valueEntry->item_variant_id = $purchaseLine->item_variant_id;
                    $valueEntry->item_description = $purchaseLine->item->description;
                    $valueEntry->item_variant_description = $purchaseLine->description;
                    $valueEntry->source_doc_type = "Purchase";
                    $valueEntry->source_doc_id = $purchaseHeader->id;
                    $valueEntry->source_doc_line_no = $purchaseLine->line_no;
                    $valueEntry->user_id = $purchaseLine->user_id;
                    $valueEntry->employee_id = $purchaseHeader->employee_id;
                    $valueEntry->posting_date = $purchaseHeader->posting_date;
                    $valueEntry->unit_price = $purchaseLine->unit_price;
                    $valueEntry->vat_percent = $purchaseLine->vat_percent;
                    $valueEntry->vat_amount = $purchaseLine->vat_amount;
                    $valueEntry->line_amount = $purchaseLine->line_amount * -1; // Wird negiert
                    $valueEntry->vendor_id = $purchaseHeader->vendor_id;
                    $valueEntry->canceled_at = null;

                    //3. Posten erfassen
                    $storageEntry->save();
                    $valueEntry->save();

                    $purchaseLine->archived_at = $carbon;
                    $purchaseLine->save();
                });

                if($purchaseHeader->open_purchase_lines()->count() < 1) {
                    $purchaseHeader->archived_at = $carbon;
                    $purchaseHeader->save();
                }
            });
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Administration\Employee;
use App\Models\SCM\Item;
use App\Models\SCM\ItemVariant;
use App\Models\SCM\Outlet;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\PurchaseLine;
use App\Models\SCM\SalesHeader;
use App\Models\SCM\SalesLine;
use App\Models\SCM\Storage;
use App\Models\SCM\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();

        Storage::factory(2)->create();
        Outlet::factory(2)->create();

        Vendor::factory(5)->create();

        Item::factory(5)->create();
        ItemVariant::factory(5)->create();

        PurchaseHeader::factory(1)->create();
        PurchaseLine::factory(10)->create();
        SalesHeader::factory(1)->create();
        SalesLine::factory(10)->create();
    }
}

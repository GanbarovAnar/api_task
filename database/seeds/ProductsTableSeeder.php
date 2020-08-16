<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();
        $json = File::get("database/data/products.json");
        $data = json_decode($json);

        foreach ($data as $obj) {
            if(strlen($obj->name) < 200)
            {
                Product::create(array(
                    'external_id' => $obj->external_id,
                    'name' => $obj->name,
                    'price' => $obj->price,
                    'category_id' => implode(",", $obj->category_id),
                    'quantity' => $obj->quantity
                ));
            }
        }
    }
}

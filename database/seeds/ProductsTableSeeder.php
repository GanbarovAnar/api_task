<?php

use App\Category;
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

        $validate = false;
        $allCategory = Category::all()->pluck('external_id')->toArray();
//        $allCategory = Category::all()->pluck('id')->toArray();

        foreach ($data as $obj) {
            if(strlen($obj->name) < 200 && is_array($obj->category_id))
            {
                if ( !empty($obj->category_id) )
                {
                    $validate = true;
                    foreach ($obj->category_id as $oneCategory)
                    {
                        if( !in_array($oneCategory, $allCategory ))
                        {
                            $validate = false;
                        }
                    }

                }else{
                    $validate = false;
                }
            }else{
                $validate = false;
            }
            //php artisan make:migration create_category_product_table
            //`category_id`, `product_id`


            if($validate)
            {
                $product = Product::create(array(
                    'external_id' => $obj->external_id,
                    'name' => $obj->name,
                    'price' => $obj->price,
                    'quantity' => $obj->quantity
                ));

                foreach ($obj->category_id as $oneCategory)
                {
                    $product->categories()->attach($oneCategory);
                }

            }
        }
    }
}

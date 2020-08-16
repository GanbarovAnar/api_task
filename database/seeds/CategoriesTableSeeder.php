<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        $json = File::get("database/data/categories.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            if(strlen($obj->name) < 200)
            {
                Category::create(array(
                    'external_id' => $obj->external_id,
                    'name' => $obj->name
                ));
            }
        }
    }
}

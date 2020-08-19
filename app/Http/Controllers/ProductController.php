<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function get(Request $request)
    {

        $sorter_price = ($request['sorter_price'] == "DESC") ? "DESC" : "ASC";
        $sorter_date  = ($request['sorter_date'] == "DESC") ? "DESC" : "ASC";

        if (isset($request['categories']))
        {
            $category = Category::find($request['categories']);

            if (is_null($category))
            {
                return response()->json([
                    'success' => false,
                    'error' => 'Нет категории с таким ID.'
                ]);
            }
            $allProduct = $category->products();

/*
// это если будет приходить массив с категориями.
// но тут немного не доделано.
// а может и вовсе неправильно сделано))
// надо получить Eloquent Builder, чтобы потом использовать метод pagination
// а метод filter() возвращает коллекцию.
            $allCategories = Category::all()->pluck('id')->toArray();
            $request_categories = $request['categories'];
            $sorter_price_desc = $request['sorter_price'] == "DESC";
            $sorter_date_desc  = $request['sorter_date'] == "DESC";
            $allProduct = Product::all()->filter(function ($oneElem) use ($request_categories){
                $categories_from_this_elem = $oneElem->categories()->get()->pluck('id')->toArray();
                return array_intersect($categories_from_this_elem, $request_categories);
            });
            $allProduct = $sorter_price_desc ? $allProduct->sortByDesc('price') : $allProduct->sortBy('price');
            $allProduct = $sorter_date_desc ? $allProduct->sortByDesc('created_at') : $allProduct->sortBy('created_at');
*/

        }else{

            $allProduct = Product::orderBy('price', $sorter_price)
                ->orderBy('created_at', $sorter_date);
        }


        $allProduct = $allProduct->paginate(50);

        return response()->json([
            'success' => true,
            'payload' => $allProduct
        ]) ;
    }


    public function show(Request $request)
    {
        if ($request['id'])
        {
            $show = Product::where('external_id', $request['id'])->first();

            if(!$show)
            {
                return response()->json([
                    'success' => false,
                    'error' => 'Нет продукта с таким ID.'
                ]);
            }

            return response()->json([
                'success' => true,
                'payload' => $show
            ]) ;
        }else{
            // 404: Not found. Ресурс не был найден.
            return response()->json([
                'success' => false,
                'error' => 'Не задан ID.'
            ]);
        }
    }

    public function create(Request $request)
    {
        $allCategory = Category::all()->pluck('id')->toArray();

        $this->validate($request, [
            'name' => 'required|max:200',
            'external_id' => 'required',
            'description' => 'required|max:1000',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => 'required'
        ]);


        if( !empty(array_diff($request['category_id'], $allCategory)) )
        {
            return response()->json([
                'success' => false,
                'error' => 'Указана несуществующая категория.'
            ]);
        }

        $product = Product::create([
            'external_id' => $request['external_id'],
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'quantity' => $request['quantity'],
        ]);

        foreach ($request['category_id'] as $oneCategory)
        {
            $product->categories()->attach($oneCategory);
        }


        // 201: Object created. Полезно при операцих сохранения и создания.
        return response()->json([
            'success' => true,
            'payload' => $product->external_id
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'max:200',
            'description' => 'max:1000',
        ]);

        $product = Product::all()->where('external_id', $request['id']);
        if (!$product)
        {
            return response()->json([
                'success' => false,
                'error' => 'Продукта с данным ID не существует.'
            ]);
        }

        $name = $request['name'] != "" ? $request['name'] : false;
        $description = $request['description'] != "" ? $request['description'] : false;
        $price = $request['price'] != "" ? $request['price'] : false;
        $quantity = $request['quantity'] != "" ? $request['quantity'] : false;
        $category_id = $request['category_id'] != "" ? $request['category_id'] : false;

        $myCompact = compact([
            'name',
            'description',
            'price',
            'quantity'
        ]);

        foreach ($myCompact as $key => $value)
        {
            if ($value)
            {
                $product->update([$key => $value]);
            }
        }

        if ($category_id)
        {
            foreach ($request['category_id'] as $oneCategory)
            {
                $product->categories()->attach($oneCategory);
            }
        }

        // 200: OK. Ответ сервера при успешном обращении, а также - стандартный ответ.
        return response()->json([
            'success' => true,
            'payload' => $product
        ]);
    }

    public function delete(Request $request)
    {
        $product = Product::where('external_id', $request['id']);

        if ( !$product )
        {
            return response()->json([
                'success' => false,
                'error' => 'Продукта с данным ID не существует.'
            ]);
        }

        $product->delete();

        // 204: No content. Когда действие было выполнено, но нет контента, который можно вернуть.
        return response()->json([
            'success' => true,
            'payload' => "Продукт удален."
        ]);
    }



}

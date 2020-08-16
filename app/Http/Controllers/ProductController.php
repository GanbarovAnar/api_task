<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get(Request $request)
    {
        $sorter_price = ($request['sorter_price'] == "DESC") ? "DESC" : "ASC";
        $sorter_date = ($request['sorter_date'] == "DESC") ? "DESC" : "ASC";

        $allProduct = Product::orderBy('price', $sorter_price)
            ->orderBy('created_at', $sorter_date);

        if (isset($request['categories']))
        {
            $allProduct->whereIn('category_id', $request['categories']);
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
            $show = Product::all()->where('external_id', $request['id']);

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
        $this->validate($request, [
            'name' => 'required|max:200',
            'external_id' => 'required',
            'description' => 'required|max:1000',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => 'required'
        ]);

        $product = Product::create([
            'external_id' => $request['external_id'],
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'quantity' => $request['quantity'],
            'category_id' =>  implode(",", $request['category_id']),
        ]);


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
            'quantity',
            'category_id'
        ]);

        foreach ($myCompact as $key => $value)
        {
            if ($value)
            {
                $product->update([$key => $value]);
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
        $product = Product::all()->where('external_id', $request['id']);

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
            'payload' => 'Продукт удален.'
        ]);
    }



}

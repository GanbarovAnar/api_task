<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get(Request $request)
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'payload' => $categories
        ]);
    }


    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:200',
            'external_id' => 'required'
        ]);

        $category = Category::create([
            'external_id' => $request['external_id'],
            'name' => $request['name'],
            'parent_category' => $request['parent_category'] ?? ''
        ]);

        return response()->json([
            'success' => true,
            'payload' => $category->external_id
        ]);
    }

    public function update(Request $request)
    {
        $category = Category::where('external_id', $request['id'])->first();
        if (!$category)
        {
            return response()->json([
                'success' => false,
                'error' => 'Категории с данным ID не существует.'
            ]);
        }
        $category->update(['name' => $request['name']]);

        return response()->json([
            'success' => true,
            'payload' => $category
        ]);
    }

    public function delete(Request $request)
    {
        $category = Category::where('external_id', $request['id'])->first();

        if ( !$category )
        {
            return response()->json([
                'success' => false,
                'error' => 'Категории с данным ID не существует.'
            ]);
        }

        $category->delete();

        // 204: No content. Когда действие было выполнено, но нет контента, который можно вернуть.
        return response()->json([
            'success' => true,
            'payload' => 'Категория удалена.'
        ]);
    }


}

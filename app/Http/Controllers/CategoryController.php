<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $parentCategories = Category::with('child')->whereNull('parent')->get();
        return view('categories.index', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'parent' => $request->parent,
        ]);

        return response()->json(['success' => true]);
    }

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent' => 'required|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'parent' => $request->parent,
        ]);

        return response()->json(['success' => true]);
    }

    public function getChild($parentId) {
        $childCategories = Category::where('parent', $parentId)->get();

        return response()->json($childCategories);
    }

}

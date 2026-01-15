<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductFilter;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        $products = (new ProductFilter($query, $request))
            ->apply()
            ->paginate(10);

        return response()->json($products);
    }
}

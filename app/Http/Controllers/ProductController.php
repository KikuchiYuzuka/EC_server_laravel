<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $product = Product::create([$request->only('title', 'description', 'image', 'price')]);

        return response($product, Response::HTTP_CREATED);
    }


    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product = Product::update([$request->only('title', 'description', 'image', 'price')]);

        return response($product, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function frontend()
    {
        if ($products = Cache::get('products_frontend')) {
            return $products;
        }

        $products = Product::all();

        Cache::set('products_frontend', $products, 30 * 60); //30min
    }

    public function backend(Request $request)
    {
        $page = $request->input('page', 1);
        /**
         * @var Collection $products
         */
        $products = Cache::remember('products_backend', 30 * 60, function() {
            return Product::all();
        });

        if ($s = $request->input('s')) {
            $products = $products
                ->filter(
                    fn(Product $product) => Str::contains($product->title, $s) || Str::contains($product->description, $s));
        }

        $total = $products->count();

        if ($sort = $request->input('sort')) {
            if ($sort === 'asc') {
                $products = $products->sortby([
                   fn($a, $b) => $a['price'] <=> $b['price']
                ]);
            } elseif ($sort === 'desc') {
                $products = $products->sortby([
                    fn($a, $b) => $b['price'] <=> $a['price']
                ]);
            }
        }

        return [
            'data' => $products->forPage($page, 9)->values(),
            'meta' => [
                'total' => $total,
                'page' => $page,
                'last_page' => ceil($total / 9),
            ]
        ];
    }
}

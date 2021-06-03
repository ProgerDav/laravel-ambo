<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        return response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->only('title', 'description', 'image', 'price'));

        return response()->json($product, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Send all products to Ambassadors front-end
     * pagination is handled by front-end
     * 
     * @return \Illuminate\Http\Response
     */
    public function frontent()
    {
        return Product::getFullCachedCollection();
    }

    /**
     * Send all products to Ambassadors front-end
     * with back-end pagination
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function backend(Request $request)
    {
        $page = $request->get('page', 1);
        $q = $request->get('s', false);
        $sort = $request->get('sort', 'asc');

        $products = Product::getFullCachedCollection();

        $products = $products->when($q, function (Collection $ps) use ($q) {
            return $ps->filter(fn (Product $p) => Str::contains($p->title, $q) || Str::contains($p->description, $q));
        });

        $products = $products->when(in_array($sort, ['asc', 'desc']), function (Collection $ps) use ($sort) {
            /**
             * Spaceship operator return -1 0 1 if a <, =, > b
             * reversing the result by multiplying it by -1 if sort is desc...
             */
            return $ps->sort(fn (Product $a, Product $b) => ($sort === 'asc' ? 1 : -1) * ($a->price <=> $b->price));
        });

        $total = $products->count();
        $perPage = 9;
        $lastPage = ceil($total / $perPage);

        return [
            'items' => $products->forPage($page, $perPage)->values(),
            'meta' => compact('total', 'page', 'perPage', 'lastPage'),
        ];
    }
}

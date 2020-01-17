<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers\Web;

use DoubleThreeDigital\SimpleCommerce\Models\Product;
use Illuminate\Http\Request;
use Statamic\View\View;

class ProductSearchController
{
    public function index()
    {
        return (new View)
            ->template('commerce::web.search')
            ->layout('commerce::web.layout')
            ->with(['title' => 'Search']);
    }

    public function show(Request $request)
    {
        $query = $request->input('query');

        // TODO: find way of refactoring this controller method

        if (! $query) {
            $results = Product::all()
                ->reject(function ($product) {
                    return ! $product->is_enabled;
                })
                ->map(function ($product) {
                    return array_merge($product->toArray(), [
                        'url' => route('products.show', ['product' => $product['slug']]),
                        'variants' => $product->variants->toArray(),
                        'from_price' => $product->variants->sortByDesc('price')->first()->price, // TODO: use the currency in here
                    ]);
                });
        } else {
            $results = Product::all()
                ->reject(function ($product) {
                    return ! $product->is_enabled;
                })
                ->filter(function ($item) use ($query) {
                    return false !== stristr((string) $item['title'], $query);
                })
                ->map(function ($product) {
                    return array_merge($product->toArray(), [
                        'url' => route('products.show', ['product' => $product['slug']]),
                        'variants' => $product->variants->toArray(),
                        'from_price' => $product->variants->sortByDesc('price')->first()->price, // TODO: use the currency in here
                    ]);
                });
        }

        return (new View)
            ->template('commerce::web.search')
            ->layout('commerce::web.layout')
            ->with([
                'results' => $results,
                'count' => $results->count(),
                'query' => $query,
            ]);
    }
}

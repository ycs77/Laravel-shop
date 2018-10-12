<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Exceptions\InvalidRequestException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $builder = Product::where('on_sale', true);

        // 處理搜尋
        if ($search = $request->input('search', '')) {
            $like = "%$search%";
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        // 處理排序
        if ($orderby = $request->input('orderby', '')) {
            if (preg_match('/^(.+)_(asc|desc)$/', $orderby, $m)) {
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $products = $builder->paginate(16);
        return view('products.index', [
            'products' => $products,
            'filters'  => [
                'search'  => $search,
                'orderby' => $orderby,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        // 判斷商品是否已上架
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }

        $favored = false;

        if ($user = $request->user()) {
            $favored = (boolean)$user->favoriteProducts()->find($product->id);
        }

        return view('products.show', ['product' => $product, 'favored' => $favored]);
    }

    /**
     * 將商品加入收藏
     *
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function favor(Request $request, Product $product)
    {
        $user = $request->user();

        if ($user->favoriteProducts()->find($product->id)) {
            return [];
        }

        $user->favoriteProducts()->attach($product);
        return [];
    }

    /**
     * 取消商品收藏
     *
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function disfavor(Request $request, Product $product)
    {
        $user = $request->user();
        $user->favoriteProducts()->detach($product);
        return [];
    }
}

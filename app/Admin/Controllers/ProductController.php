<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductEditRequest;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param  \Encore\Admin\Layout\Content  $content
     * @return \Encore\Admin\Layout\Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('商品列表')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param  int  $id
     * @param  \Encore\Admin\Layout\Content  $content
     * @return \Encore\Admin\Layout\Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('商品')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Create interface.
     *
     * @param  \Encore\Admin\Layout\Content  $content
     * @return \Encore\Admin\Layout\Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('新增商品')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Edit interface.
     *
     * @param  int  $id
     * @param  \Encore\Admin\Layout\Content  $content
     * @return \Encore\Admin\Layout\Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('編輯商品')
            ->description('description')
            ->body($this->form($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $this->save($request);

        admin_toastr(trans('admin.save_succeeded'));

        return redirect(admin_url('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductEditRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductEditRequest $request, $id)
    {
        $this->save($request, $id);

        admin_toastr(trans('admin.update_succeeded'));

        return redirect(admin_url('products'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Product::destroy($id)) {
            $data = [
                'status' => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status' => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }

    /**
     * Make a grid builder.
     *
     * @return \Encore\Admin\Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->id('ID')->sortable();
        $grid->image('封面圖片')->image();
        $grid->title('商品名稱');
        $grid->on_sale('上架狀態')->display(function ($value) {
            return $value
                ? '<span class="label label-success">已上架</span>'
                : '<span class="label label-danger">未上架</span>';
        });
        $grid->price('價格')->display(function ($price) {
            return '$' . $price;
        });
        $grid->rating('評分');
        $grid->sold_count('銷量');
        $grid->review_count('評論數');

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param  int  $id
     * @return \Encore\Admin\Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->id('ID');
        $show->title('商品名稱');
        $show->image('封面圖片')->image();
        $show->description('商品介紹')->unescape();
        $show->on_sale('是否上架')->unescape()->as(function ($value) {
            return $value
                ? '<span class="label label-success">已上架</span>'
                : '<span class="label label-danger">未上架</span>';
        });
        $show->price('價格')->as(function ($price) {
            return '$' . $price;
        });
        $show->rating('評分');
        $show->sold_count('銷量');
        $show->review_count('評論數');
        $show->created_at('新增日期');

        return $show;
    }

    /**
     * Make a form view.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    protected function form($id = null)
    {
        $product = Product::firstOrNew(compact('id'));

        return view('admin.products.form', compact('product'));
    }

    /**
     * Save product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \App\Models\Product
     */
    protected function save(Request $request, $id = null)
    {
        $data = $request->only(['title', 'image', 'description', 'on_sale', 'price']);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = $data['image']->store(
                config('admin.upload.directory.image'),
                ['disk' => config('admin.upload.disk')]
            );
            $data['image'] = Storage::disk(config('admin.upload.disk'))->url($imagePath);
        }

        $data['on_sale'] = is_string($data['on_sale']) ? $data['on_sale'] === 'on' : $data['on_sale'];

        // Update or create porduct
        $product = Product::updateOrCreate(['id' => $id], $data);

        // Clear old product attributes
        $product->attrs->map->delete();

        // Create new product attributes
        $attrs = collect($request->input('attrs'));
        $attrs->each(function ($attr) use ($product) {
            $product->attrs()->create($attr);
        });

        // Clear old product skus
        $product->skus->map->delete();

        // Create new product skus
        $skus = collect($request->input('skus'));
        $skus->each(function ($sku) use ($product) {
            if (is_string($sku['attr_items_index'])) {
                $sku['attr_items_index'] = json_decode($sku['attr_items_index'], true);
            }
            $product->skus()->create($sku);
        });

        // Update product price
        if ($min = $skus->min('price')) {
            $product->update(['price' => $min]);
        }

        return $product;
    }
}

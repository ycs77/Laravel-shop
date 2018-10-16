<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrderController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('訂單列表')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $order = Order::find($id);

        return $content
            ->header('查看訂單')
            ->description('description')
            ->body(view('admin.orders.show', ['order' => $order]));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->model()->whereNotNull('paid_at')->orderBy('paid_at', 'desc');

        $grid->no('訂單流水號');
        $grid->column('user.name', '買家');
        $grid->total_amount('總金額')->sortable();
        $grid->paid_at('付款時間')->sortable();
        $grid->ship_status('物流')->display(function ($value) {
            return __("order.ship.{$value}");
        });
        $grid->refund_status('退款狀態')->display(function ($value) {
            return __("order.refund.{$value}");
        });

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }
}

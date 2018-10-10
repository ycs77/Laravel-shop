<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
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
            ->header('用戶列表')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('ID');
        $grid->name('姓名');
        $grid->email('E-mail');
        $grid->email_verified_at('是否驗證E-mail')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->created_at('註冊日期');

        $grid->disableCreateButton(); // 禁用新增按鈕
        $grid->disableExport(); // 禁用匯出按鈕

        $grid->actions(function ($actions) {
            $actions->disableView();   // 不在每一行後面展示查看按鈕
            $actions->disableDelete(); // 不在每一行後面展示刪除按鈕
            $actions->disableEdit();   // 不在每一行後面展示編輯按鈕
        });

        $grid->tools(function ($tools) {
            // 禁用批量刪除按鈕
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->email('Email');
        $show->email_verified_at('Email verified at');
        $show->password('Password');
        $show->remember_token('Remember token');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }
}

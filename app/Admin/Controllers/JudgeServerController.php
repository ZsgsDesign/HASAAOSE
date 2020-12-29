<?php

namespace App\Admin\Controllers;

use App\Models\Eloquent\JudgeServerModel;
use App\Models\JudgerModel;
use App\Models\Eloquent\OJ;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class JudgeServerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '评测机';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new JudgeServerModel());

        $grid->column('jsid', 'Jsid');
        $grid->column('scode', 'Scode');
        $grid->column('name', 'Name');
        $grid->column('host', 'Host');
        $grid->column('port', 'Port');
        $grid->column('token', 'Token');
        $grid->column('available', 'Available')->display(function ($available) {
            return $available?"Available":"Unavailable";
        });
        $grid->column('OJ', 'OJ')->display(function () {
            return $this->oj->name;
        });
        $grid->column('usage', 'Usage')->display(function ($usage) {
            return "$usage%";
        });
        $grid->column('status', 'Status')->display(function ($status) {
            $status = JudgerModel::$status[$status];
            return '<i class="MDI '.$status['icon'].' '.$status['color'].'"></i> '.$status['text'];
        });
        $grid->column('status_update_at', 'Status update at');
        $grid->column('created_at', 'Created at');
        $grid->column('updated_at', 'Updated at');
        $grid->column('deleted_at', 'Deleted at');

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
        $show = new Show(JudgeServerModel::findOrFail($id));

        $show->field('jsid', 'Jsid');
        $show->field('scode', 'Scode');
        $show->field('name', 'Name');
        $show->field('host', 'Host');
        $show->field('port', 'Port');
        $show->field('token', 'Token');
        $show->field('available', 'Available')->as(function ($available) {
            return $available?"Available":"Unavailable";
        });
        $show->field('oj.name', 'OJ');
        $show->field('usage', 'Usage')->as(function ($usage) {
            return "$usage%";
        });
        $show->field('status', 'Status')->unescape()->as(function ($status) {
            $status = JudgerModel::$status[$status];
            return '<i class="MDI '.$status['icon'].' '.$status['color'].'"></i> '.$status['text'];
        });
        $show->field('status_update_at', 'Status update at');
        $show->field('created_at', 'Created at');
        $show->field('updated_at', 'Updated at');
        $show->field('deleted_at', 'Deleted at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new JudgeServerModel());

        $form->text('scode', 'Scode')->required();
        $form->text('name', 'Name')->required();
        $form->text('host', 'Host')->required();
        $form->text('port', 'Port')->required();
        $form->text('token', 'Token')->required();
        $form->switch('available','Available');
        $form->select('oid', "OJ")->options(OJ::all()->pluck('name', 'oid'))->required();

        return $form;
    }
}

<?php

namespace App\Admin\Controllers;

use App\Models\Eloquent\Submission;
use App\Http\Controllers\Controller;
use App\Models\Eloquent\Contest;
use App\Models\Eloquent\Problem;
use App\Models\Eloquent\UserModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Arr;

class SubmissionController extends Controller
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
            ->header('提交')
            ->description('所有提交')
            ->body($this->grid()->render());
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
        return $content
            ->header('Submission Detail')
            ->description('the detail of submissions')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit Submission')
            ->description('edit the detail of submissions')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create New Submission')
            ->description('create a new submission')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid=new Grid(new Submission);
        $grid->column('sid', "ID")->sortable();
        $grid->column("time", "时间占用")->display(function ($time) {
            return "{$time}毫秒";
        });
        $grid->column("memory", "空间占用")->display(function ($memory) {
            return "{$memory}千比特";
        });
        $grid->column('verdict', "结果")->display(function ($verdict) {
            return '<i class="fa fa-circle '.$this->color.'"></i> '.$verdict;
        });
        $grid->column("language", "编程语言");
        $grid->column("submission_date", "提交日期")->display(function ($submission_date) {
            return date("Y-m-d H:i:s", $submission_date);
        });
        $grid->column("user_name","用户名称")->display(function () {
            return $this->user->name;
        });
        $grid->column("contest_name","比赛名称")->display(function () {
            if(!is_null($this->contest)) return $this->contest->name;
        });
        $grid->column("problem_pcode","题目名称")->display(function () {
            return $this->problem->readable_name;
        });
        $grid->column("judger_name","评测机")->display(function () {
            return $this->judger_name;
        });
        $grid->column("parsed_score","得分")->display(function () {
            return $this->parsed_score;
        });
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(6, function ($filter) {
                $filter->like('verdict');
            });
            $filter->column(6, function ($filter) {
                $filter->equal('cid', '考试')->select(Contest::all()->pluck('name', 'cid'));
                $filter->equal('uid', '用户')->select(UserModel::all()->pluck('name', 'id'));
                $filter->equal('pid', '题目')->select(Problem::all()->pluck('readable_name', 'pid'));
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
        $show=new Show(Submission::findOrFail($id));
        $show->sid('SID');
        $show->time();
        $show->memory();
        $show->verdict();
        $show->color();
        $show->language();
        $show->submission_date();
        $show->remote_id();
        $this->codify($show->solution(), $show->getModel()->compiler->lang);
        if (!blank($show->getModel()->compile_info)) {
            $this->codify($show->compile_info());
        }
        $show->uid('UID');
        $show->pid('PID');
        $show->cid('CID');
        $show->jid('JID');
        $show->coid('COID');
        $show->vcid('VCID');
        $show->score();
        $show->share()->using(['No','Yes']);
        return $show;
    }

    private function codify($field, $lang=null)
    {
        $field->unescape()->as(function ($value) use ($field,$lang) {
            $field->border = false;
            $hash=md5($value);
            if (blank($value)) {
                $value=" ";
            }
            return "
                <style>
                #x$hash {
                    background: #ffffff;
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                    border-bottom-right-radius: 3px;
                    border-bottom-left-radius: 3px;
                    padding: 10px;
                    border: 1px solid #d2d6de;
                }
                #x$hash code {
                    background: #ffffff;
                }
                </style>
                <pre id='x$hash'><code class='$lang'>".htmlspecialchars($value)."</code></pre>
                <script>
                    try{
                        hljs.highlightBlock(document.querySelector('#x$hash code'));
                    }catch(err){
                        window.addEventListener('load', function(){hljs.highlightBlock(document.querySelector('#x$hash code'));});
                    }
                </script>
            ";
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form=new Form(new Submission);
        $form->model()->makeVisible('password');
        $form->tab('Basic', function (Form $form) {
            $form->display('sid');
            $form->text('time')->rules('required');
            $form->text('memory')->rules('required');
            $form->text('verdict')->rules('required');
            $form->text('color')->rules('required');
            $form->text('language')->rules('required');
            $form->display('submission_date');
            $form->number('uid')->rules('required');
            $form->number('cid');
            $form->number('pid')->rules('required');
            $form->number('jid')->rules('required');
            $form->number('coid')->rules('required');
            $form->number('score')->rules('required');
        });
        return $form;
    }
}

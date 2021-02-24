<?php

namespace App\Admin\Controllers;

use App\Models\Eloquent\Problem as EloquentProblemModel;
use App\Models\Eloquent\OJ as EloquentOJModel;
use App\Http\Controllers\Controller;
use App\Admin\Forms\ImportPOEM;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class ProblemController extends Controller
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
            ->header('题目')
            ->description('所有题目')
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
            ->header('题目详情')
            ->description('题目的详细信息')
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
            ->header('编辑题目')
            ->description('编辑题目的详情')
            ->body($this->form()->edit($id));
    }

    /**
     * Import interface.
     *
     * @param Content $content
     * @return Content
     */
    public function import(Content $content)
    {
        return $content
            ->header('导入题目')
            ->description('由POEM或POETRY导入题目')
            ->body(new ImportPOEM());
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
            ->header('创建新的题目')
            ->description('创建新的题目')
            ->body($this->form(true));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid=new Grid(new EloquentProblemModel);
        $grid->column('pid', "ID")->sortable();
        $grid->column('pcode', "题目代码")->editable();
        $grid->column("title", '题目标题')->editable();
        // $grid->solved_count();
        $grid->column("time_limit", '时间限制/ms')->editable();
        $grid->column("memory_limit", '空间限制/kb')->editable();
        // $grid->OJ();
        // $grid->update_date();
        $grid->column("tot_score", '测试点数量');
        // $grid->partial("Partial")->display(function($partial) {
        //     return $partial ? 'Yes' : 'No';
        // });
        // $grid->markdown("Markdown")->display(function($markdown) {
        //     return $markdown ? 'Yes' : 'No';
        // });
        // $grid->order_index("order")->sortable();
        $grid->filter(function(Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('pcode','题目编号');
            $filter->like('title','题目标题');
        });

        // $grid->disableCreateButton();

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
        $show=new Show(EloquentProblemModel::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder for create view and edit.
     *
     * @return Form
     */
    protected function form($create = false)
    {
        $form=new Form(new EloquentProblemModel);
        $form->model()->makeVisible('password');
        $form->tab('Basic', function(Form $form){
            $form->text('pid', 'ID')->readonly();
            $form->text('pcode', '题目代码')->rules('required');
            $form->text('title', '题目标题')->rules('required');
            $form->text('time_limit', '时间限制')->rules('required');
            $form->text('memory_limit', '内存限制')->rules('required');
            $form->simplemde('description', '题目描述')->rules('required');
            $form->simplemde('input', '题目输入');
            $form->simplemde('output', '题目输出');
            $form->simplemde('note', '题目备注');
            $form->hasMany('problem_samples', '题目样例', function (Form\NestedForm $form) {
                $form->textarea('sample_input', '样例输入')->rows(3);
                $form->textarea('sample_output', '样例输出')->rows(3);
                $form->textarea('sample_note', '样例备注')->rows(3);
            });
            /* $form->table('samples', function ($table) {
                $table->textarea('sample_input', 'sample input');
                $table->textarea('sample_output', 'sample output');
                $table->textarea('sample_note', 'sample note');
            }); */
            $ojs_temp = EloquentOJModel::select('oid', 'name')->get()->all();
            $ojs = [];
            foreach($ojs_temp as $v){
                $ojs[$v->oid] = $v->name;
            }
            $form->select('oj', '评测系统')->options($ojs)->default(1)->rules('required');
            /* $form->display('update_date'); */
            /* $form->text('tot_score')->rules('required');
            $form->select('partial', 'Partial Score')->options([
                0 => "No",
                1 => "Yes"
            ])->rules('required'); */
            $form->radio('Hide', '隐藏题目')
                ->options([
                    0 => '否',
                    1 => '是'
                ])->default(0)->rules('required');
            $form->radio('spj', '使用SPJ')
                ->options([
                    0 => '否',
                    1 => '是'
                ])->default(0)->rules('required');
            $form->clang('spj_src','SPJ源代码');
            $form->file('test_case', '评测数据')->rules('required');
            $form->ignore(['test_case']);

            //Hidden parameters

            $form->hidden('markdown');
            $form->hidden('input_type');
            $form->hidden('output_type');
            $form->hidden('solved_count');
            $form->hidden('difficulty');
            $form->hidden('partial');
            $form->hidden('tot_score');
            $form->hidden('file');
            $form->hidden('spj_lang');
            $form->hidden('spj_version');
        });
        /* if($create){
            $form->tools(function (Form\Tools $tools) {
                $tools->append('<a href="/'.config('admin.route.prefix').'/problems/import" class="btn btn-sm btn-success" style="margin-right:1rem"><i class="MDI file-powerpoint-box"></i>&nbsp;&nbsp;Import from file</a>');
            });
        } */
        $form->saving(function (Form $form){
            $err = function ($msg, $title = 'Test case file parse faild.') {
                $error = new MessageBag([
                    'title'   => $title,
                    'message' => $msg,
                ]);
                return back()->with(compact('error'));
            };
            $pcode = $form->pcode;
            $p = EloquentProblemModel::where('pcode',$pcode)->first();
            //check pcode has been token.
            $pid = $form->pid ?? null;
            if(!empty($p) && $p->pid != $pid){
                $err('Pcode has been token', 'Error occur.');
            }
            $test_case = \request()->file('test_case');
            //Make sure the user enters SPJ_SRc in spj problem.
            if($form->spj && empty($form->spj_src)) {
                $err('The SPJ problem must provide spj_src', 'create problem error');
            }
            //check info file. Try to generate if it does not exist.
            $info_content = [];
            if(!empty($test_case)){
                if($test_case->extension() != 'zip'){
                    $err('You must upload a zip file iuclude test case info and content.');
                }
                $path = $test_case->path();
                $zip = new ZipArchive;
                if($zip->open($path) !== true) {
                    $err('You must upload a zip file without encrypt and can open successfully.');
                };
                $info_content = [];
                if(($zip->getFromName('info')) === false){
                    if(!$form->spj){
                        $info_content = [
                            'spj' => false,
                            'test_cases' => []
                        ];
                        $files = [];
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            $files[] = $filename;
                        }
                        $files_in = array_filter($files, function ($filename) {
                            return strpos('.in', $filename) != -1;
                        });
                        sort($files_in);
                        $testcase_index = 1;
                        foreach($files_in as $filename_in){
                            $filename = basename($filename_in, '.in');
                            $filename_out = $filename.'.out';
                            if(($zip->getFromName($filename_out)) === false) {
                                continue;
                            }
                            $test_case_in = $zip->getFromName($filename_in);
                            $test_case_out = $zip->getFromName($filename_out);
                            $info_content['test_cases']["{$testcase_index}"] = [
                                'input_size' => strlen($test_case_in),
                                'input_name' => $filename_in,
                                'output_size' => strlen($test_case_out),
                                'output_name' => $filename_out,
                                'stripped_output_md5' => md5(utf8_encode(rtrim($test_case_out)))
                            ];
                            $testcase_index += 1;
                        }
                    }else{
                        $info_content = [
                            'spj' => true,
                            'test_cases' => []
                        ];
                        $files = [];
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            $files[] = $filename;
                        }
                        $files_in = array_filter($files, function ($filename) {
                            return strpos($filename, '.in') !== false;
                        });
                        sort($files_in);
                        $testcase_index = 1;
                        foreach($files_in as $filename_in){
                            $test_case_in = $zip->getFromName($filename_in);
                            $info_content['test_cases']["{$testcase_index}"] = [
                                'input_size' => strlen($test_case_in),
                                'input_name' => $filename_in
                            ];
                            $testcase_index += 1;
                        }
                    }
                    $zip->addFromString('info', json_encode($info_content));
                    $zip->close();
                    //$err('The zip files must include a file named info including info of test cases, and the format can see ZsgsDesign/NOJ wiki.');
                }else{
                    $info_content = json_decode($zip->getFromName('info'),true);
                };
                $zip->open($path);
                //If there is an INFO file, check that the contents of the file match the actual situation
                $test_cases = $info_content['test_cases'];
                //dd($test_cases);
                foreach($test_cases as $index => $case) {
                    if(!isset($case['input_name']) || (!$form->spj && !isset($case['output_name']))) {
                        $err("Test case index {$index}: configuration missing input/output files name.");
                    }
                    if($zip->getFromName($case['input_name']) === false || (!$form->spj && $zip->getFromName($case['output_name']) === false)) {
                        $err("Test case index {$index}: missing input/output files that record in the configuration.");
                    }
                }
                if(!empty($form->pid)){
                    $problem = EloquentProblemModel::find($form->pid);
                    if(!empty($problem)){
                        $pcode = $problem->pcode;
                    }else{
                        $pcode = $form->pcode;
                    }
                }else{
                    $pcode = $form->pcode;
                }

                if(Storage::exists(base_path().'/storage/test_case/'.$pcode)){
                    Storage::deleteDirectory(base_path().'/storage/test_case/'.$pcode);
                }
                Storage::makeDirectory(base_path().'/storage/test_case/'.$pcode);
                $zip->extractTo(base_path().'/storage/test_case/'.$pcode.'/');

            }
            //Set the spj-related data
            if($form->spj){
                $form->spj_lang = 'c';
                $form->spj_version = "{$form->pcode}#".time();
            }
            //Set default data
            $form->tot_score = count($info_content['test_cases']);
            $form->markdown = true;
            $form->input_type = 'standard input';
            $form->output_type = 'standard output';
            $form->solved_count = 0;
            $form->difficulty = -1;
            $form->partial = 1;
            $form->file = 0;
        });
        return $form;
    }
}

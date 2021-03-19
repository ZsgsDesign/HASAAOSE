<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CompilerModel;

class StatusModel extends Model
{
    protected $tableName="submission";
    protected $extractModels=[
        "SubmissionModel"=>null
    ];

    public function __construct($submissionModel)
    {
        $this->extractModels["SubmissionModel"]=$submissionModel;
    }

    public function getJudgeStatus($sid, $uid)
    {
        $status=$this->extractModels["SubmissionModel"]->basic($sid);
        if (empty($status)) {
            return [];
        }
        $ret = [
            "sid" => $status["sid"],
            "time" => $status["time"],
            "verdict" => $status["verdict"],
            "color" => $status["color"],
            "solution" => $status["solution"],
            "language" => $status["language"],
            "submission_date" => $status["submission_date"],
            "memory" => $status["memory"],
            "compile_info" => $status["compile_info"],
            "score" => $status["score"],
        ];
        if ($status["share"]==1 && $status["cid"]) {
            $end_time=strtotime(DB::table("contest")->where(["cid"=>$status["cid"]])->select("end_time")->first()["end_time"]);
            if (time()<$end_time) {
                $ret["solution"]=null;
                $ret['compile_info']="You don't have the permission to view this compile info.";
            }
        }
        if ($status["share"]==0 && $status["uid"]!=$uid) {
            $ret["solution"]=null;
            $ret['compile_info']="You don't have the permission to view this compile info.";
        }
        if($status['cid']){
            // HASAAOSE Judged Status Special Procedure
            if (in_array($status["verdict"], [
                "Runtime Error",
                "Wrong Answer",
                "Time Limit Exceed",
                "Real Time Limit Exceed",
                "Accepted",
                "Memory Limit Exceed",
                "Presentation Error",
                "Partially Accepted",
                "Output Limit Exceeded",
                "Idleness Limit Exceed",
            ])) {
                # Turn into Judged Status
                $ret["verdict"] = "Judged";
                $ret["color"] = "wemd-indigo-text";
                $ret["score"] = 0;
                $ret["time"] = 0;
                $ret["memory"] = 0;
            }
            # would not show solution source code
            $ret["solution"]=null;
        }
        $compilerModel=new CompilerModel();
        $ret["lang"]=$compilerModel->detail($status["coid"])["lang"];
        $ret["owner"]=$uid==$status["uid"];
        return $ret;
    }

    public function downloadCode($sid, $uid)
    {
        $status=DB::table($this->tableName)->where(['sid'=>$sid])->first();
        if (empty($status) || ($status["share"]==0 && $status["uid"]!=$uid)) {
            return [];
        }
        $lang=DB::table("compiler")->where(['coid'=>$status["coid"]])->first()["lang"];
        $curLang=isset($this->extractModels["SubmissionModel"]->langConfig[$lang]) ? $this->extractModels["SubmissionModel"]->langConfig[$lang] : $this->extractModels["SubmissionModel"]->langConfig["plaintext"];
        return [
            "content"=>$status["solution"],
            "name"=>$status["submission_date"].$curLang["extensions"][0],
        ];
    }

    public function getProblemStatus($pid, $uid, $cid=null)
    {
        if ($cid) {
            $end_time=strtotime(DB::table("contest")->where(["cid"=>$cid])->select("end_time")->first()["end_time"]);
            // Get the very first non-CE record
            $nonError=DB::table($this->tableName)->where([
                'pid'=>$pid,
                'uid'=>$uid,
                'cid'=>$cid,
            ])->whereNotIn("color", ['wemd-orange-text', 'wemd-black-text'])->where("submission_date", "<", $end_time)->orderBy('submission_date', 'desc')->first();
            if (empty($nonError)) {
                $error=DB::table($this->tableName)->where([
                    'pid'=>$pid,
                    'uid'=>$uid,
                    'cid'=>$cid,
                ])->whereIn("color", ['wemd-orange-text', 'wemd-black-text'])->where("submission_date", "<", $end_time)->orderBy('submission_date', 'desc')->first();
                $ret = $error;
            } else {
                $ret = $nonError;
            }
            if(!empty($ret)){
                if (in_array($ret["verdict"], [
                    "Runtime Error",
                    "Wrong Answer",
                    "Time Limit Exceed",
                    "Real Time Limit Exceed",
                    "Accepted",
                    "Memory Limit Exceed",
                    "Presentation Error",
                    "Partially Accepted",
                    "Output Limit Exceeded",
                    "Idleness Limit Exceed",
                ])) {
                    # Turn into Judged Status
                    $ret["verdict"] = "Judged";
                    $ret["color"] = "wemd-indigo-text";
                    $ret["score"] = 0;
                    $ret["time"] = 0;
                    $ret["memory"] = 0;
                }
            }
            return $ret;
        } else {
            $ac=DB::table($this->tableName)->where([
                'pid'=>$pid,
                'uid'=>$uid,
                'cid'=>$cid,
                'verdict'=>'Accepted'
            ])->orderBy('submission_date', 'desc')->first();
            return empty($ac) ? DB::table($this->tableName)->where(['pid'=>$pid, 'uid'=>$uid, 'cid'=>$cid])->orderBy('submission_date', 'desc')->first() : $ac;
        }
    }
}

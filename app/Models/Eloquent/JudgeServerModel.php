<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JudgeServerModel extends Model
{
    protected $table='judge_server';
    protected $primaryKey='jsid';

    protected $fillable=[
        'scode', 'name', 'host', 'port', 'token', 'available', 'oid', 'usage', 'status', 'status_update_at'
    ];

    public static function column($key)
    {
        return Self::groupBy($key)->whereNotNull($key)->pluck($key)->toArray();
    }

    public function oj() {
        return $this->belongsTo('App\Models\Eloquent\OJ','oid','oid');
    }
}

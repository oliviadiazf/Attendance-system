<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $table = 'absen';
    protected $fillable = ['user_id','date','time_in','time_out','total_time','note_tugas','note_kendala'];
    protected $_GET = ['time_in','time_out'];
    protected $_POST = ['time_total'];
}

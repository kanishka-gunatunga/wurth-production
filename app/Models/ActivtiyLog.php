<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivtiyLog extends Model
{
    use HasFactory;

    protected $table = 'activtiy_log';
    protected $primaryKey = 'id';

     public function userData()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
   
}

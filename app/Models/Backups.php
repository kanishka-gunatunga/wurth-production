<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backups extends Model
{
    use HasFactory;

    protected $table = 'backups';
    protected $primaryKey = 'id';

        protected $fillable = [
        'file_name',
        'path',
        'disk',
        'size',
        'status'
    ];


}

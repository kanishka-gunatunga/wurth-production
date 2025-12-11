<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id';

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }
}

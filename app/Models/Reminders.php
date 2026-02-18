<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminders extends Model
{
    use HasFactory;

    protected $table = 'reminders';
    protected $primaryKey = 'id';

    protected $casts = [
        'send_to' => 'array',
    ];

    public function user()
    {
        // adm_id in inquiries → id in users
        return $this->belongsTo(User::class, 'sent_user_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_user_id', 'id');
    }

   
}

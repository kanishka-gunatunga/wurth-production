<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCheque extends Model
{
    use HasFactory;

    protected $table = 'return_cheques';

    protected $fillable = [
        'adm_id',
        'cheque_number',
        'cheque_amount',
        'returned_date',
        'bank_id',
        'branch_id',
        'return_type',
        'reason',
    ];

    public function adm()
    {
        return $this->belongsTo(User::class, 'adm_id');
    }
}

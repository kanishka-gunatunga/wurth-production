<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetOffs extends Model
{
    protected $table = 'set_offs';

    protected $fillable = [
        'invoice_or_cheque_no',
        'extraPayment_or_creditNote_no',
        'final_amount',
        'reason',
    ];

    protected $casts = [
        'invoice_or_cheque_no' => 'array',
        'extraPayment_or_creditNote_no' => 'array',
    ];
}

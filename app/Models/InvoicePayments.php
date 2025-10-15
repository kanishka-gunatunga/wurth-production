<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    use HasFactory;

    protected $table = 'invoice_payments';
    protected $primaryKey = 'id';

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Invoices::class, 'invoice_id', 'id');
    }
}

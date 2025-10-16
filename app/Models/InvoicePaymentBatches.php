<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentBatches extends Model
{
    use HasFactory;

    protected $table = 'invoice_payment_batches';
    protected $primaryKey = 'id';

    /**
     * Each batch contains multiple invoice payments.
     * 
     * invoice_payments.batch_id → invoice_payment_batches.id
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayments::class, 'batch_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    use HasFactory;

    protected $table = 'invoice_payments';
    protected $primaryKey = 'id';


    /**
     * Each payment belongs to one invoice.
     * 
     * invoice_payments.invoice_id → invoices.id
     */
    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }

    /**
     * Each payment belongs to one batch.
     * 
     * invoice_payments.batch_id → invoice_payment_batches.id
     */
    public function batch()
    {
        return $this->belongsTo(InvoicePaymentBatches::class, 'batch_id', 'id');
    }
    public function invoice()
    {
        return $this->belongsTo(\App\Models\Invoices::class, 'invoice_id', 'id');
    }
}

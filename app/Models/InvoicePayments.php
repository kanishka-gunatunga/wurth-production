<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    use HasFactory;

    protected $table = 'invoice_payments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'invoice_id',
        'uniqid',
        'batch_id',
        'adm_id',
        'type',
        'is_bulk',
        'amount',
        'discount',
        'final_payment',
        'transfer_date',
        'transfer_reference_number',
        'screenshot',
        'cheque_number',
        'cheque_amount',
        'cheque_date',
        'cheque_image',
        'bank_name',
        'branch_name',
        'post_dated',
        'card_transfer_date',
        'card_image',
        'status',
        'pdf_path',
        'duplicate_pdf',
        'created_at',
        'updated_at',
    ];
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

    public function adm()
    {
        return $this->belongsTo(User::class, 'adm_id', 'id');
    }

    public function customer()
    {
        return $this->invoice->customer ?? null;
    }
}

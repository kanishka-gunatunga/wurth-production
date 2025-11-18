<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentBatches extends Model
{
    use HasFactory;

    protected $table = 'invoice_payment_batches';
    protected $primaryKey = 'id';
    protected $appends = ['division'];

    /**
     * Each batch contains multiple invoice payments.
     * 
     * invoice_payments.batch_id → invoice_payment_batches.id
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayments::class, 'batch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'adm_id', 'id');
    }

    public function admDetails()
    {
        return $this->hasOneThrough(
            UserDetails::class,
            User::class,
            'id',
            'user_id',
            'adm_id',
            'id'
        );
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customers::class,
            InvoicePayments::class,
            'batch_id',       // invoice_payments.batch_id → batches.id
            'customer_id',    // customers.customer_id → invoices.customer_id
            'id',
            'invoice_id'
        );
    }

    public function getDivisionAttribute()
    {
        return $this->admDetails->division ?? null;
    }
}

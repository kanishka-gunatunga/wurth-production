<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'id';

    /**
     * Each invoice belongs to one customer.
     * 
     * invoices.customer_id → customers.customer_id
     */
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    /**
     * Each invoice can have multiple inquiries linked to it.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiries::class, 'invoice_number', 'id');
    }

    /**
     * Each invoice can have multiple payments.
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayments::class, 'invoice_id', 'id');
    }
}
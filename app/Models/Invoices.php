<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'invoice_or_cheque_no',
        'customer_id',
        'invoice_date',
        'paid_amount',
        'amount',
        'updated_amount',
        'write_off_amount',
        'returned_date',
        'bank',
        'branch',
        'return_type',
        'reason',
        'reference',
    ];


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


    public function admDetails()
    {
        return $this->hasOneThrough(
            \App\Models\UserDetails::class,
            \App\Models\Customers::class,
            'customer_id',    // Foreign key on customers table
            'adm_number',     // Foreign key on user_details table
            'customer_id',    // Local key on invoices table
            'adm'             // Local key on customers table
        );
    }
}

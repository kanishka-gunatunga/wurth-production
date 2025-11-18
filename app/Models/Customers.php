<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id';


    /**
     * A customer can have many inquiries.
     */
     public function inquiries()
        {
            return $this->hasMany(Inquiries::class, 'customer', 'id');
        }

    /**
     * A customer can have many invoices.
     */
        public function invoices()
        {
                return $this->hasMany(Invoices::class, 'customer_id', 'customer_id');
        }

    /**
     * A customer can have many advance payments.
     */
        public function advance_payments()
        {
                return $this->hasMany(AdvancedPayment::class, 'customer', 'id');
        }

    /**
     * The ADM (Account/Area Manager) assigned to this customer.
     * 
     * Links customers.adm → user_details.adm_number
     */
    public function admDetails()
    {
        return $this->belongsTo(UserDetails::class, 'adm', 'adm_number');
    }

     public function secondaryAdm()
    {
        return $this->belongsTo(UserDetails::class, 'secondary_adm', 'adm_number');
    }
    public function userDetail()
    {
        return $this->belongsTo(\App\Models\UserDetails::class, 'adm', 'adm_number');
    }
    public function creditNote()
    {
        return $this->hasMany(CreditNote::class, 'customer_id', 'customer_id');
    }
    public function extraPayment()
    {
        return $this->hasMany(ExtraPayment::class, 'customer_id', 'customer_id');
    }

    public function invoicePayments()
{
    return $this->hasManyThrough(
        InvoicePayments::class, // Final model
        Invoices::class,        // Intermediate model
        'customer_id',          // Foreign key on invoices table (link to customer)
        'invoice_id',           // Foreign key on invoice_payments table (link to invoice)
        'customer_id',          // Local key on customers table
        'id'                    // Local key on invoices table
    );
}
}

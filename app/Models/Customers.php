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
                return $this->hasMany(Invoices::class, 'customer_id', 'id');
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

    public function userDetail()
    {
        return $this->belongsTo(\App\Models\UserDetails::class, 'adm', 'adm_number');
    }
}

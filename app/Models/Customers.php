<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id';

   public function inquiries()
{
    return $this->hasMany(Inquiries::class, 'customer', 'id');
}

public function invoices()
{
    // invoices.customer_id → customers.customer_id
    return $this->hasMany(Invoices::class, 'customer_id', 'customer_id');
}
public function advance_payments()
{
    // invoices.customer_id → customers.customer_id
    return $this->hasMany(AdvancedPayment::class, 'customer', 'id');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedPayment extends Model
{
    use HasFactory;

    protected $table = 'advanced_payments';
    protected $primaryKey = 'id';

  public function customerData()
{
    return $this->belongsTo(Customers::class, 'customer', 'customer_id');
}



    public function admin()
    {
        // adm_id in inquiries → id in users
        return $this->belongsTo(User::class, 'adm_id', 'id');
    }
}

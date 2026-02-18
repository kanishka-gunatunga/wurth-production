<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraPayment extends Model
{
    use HasFactory;

    protected $table = 'extra_payment'; // your table name
    protected $primaryKey = 'id';

    protected $fillable = [
        'extra_payment_id',
        'customer_id',
        'customer_name',
        'adm_id',
        'adm_name',
        'amount',
        'updated_amount',
    ];

    /**
     * Each credit note belongs to a customer
     */
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    public function admDetails()
    {
        return $this->belongsTo(UserDetails::class, 'adm_id', 'adm_number');
    }
}

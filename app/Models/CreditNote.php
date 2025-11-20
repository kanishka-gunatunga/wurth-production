<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use HasFactory;

    protected $table = 'credit_note'; // your table name
    protected $primaryKey = 'id';

    protected $fillable = [
        'credit_note_id',
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
}

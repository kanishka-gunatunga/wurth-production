<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceRequest extends Model
{
    use HasFactory;

    protected $table = 'invoice_requests';

    protected $fillable = [
        'user_id',
        'name',
        'mobile_number',
        'address',
        'invoice_no',
        'invoice_date',
        'total_amount',
        'status',
        'payment_id',
    ];

    /**
     * Get the user who made the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the payment associated with this invoice request.
     */
    public function payment()
    {
        return $this->belongsTo(InvoiceRequestPayment::class, 'payment_id');
    }
}

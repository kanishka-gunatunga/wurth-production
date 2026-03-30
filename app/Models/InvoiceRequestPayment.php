<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceRequestPayment extends Model
{
    use HasFactory;

    protected $table = 'invoice_request_payments';

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    /**
     * A payment has many invoice requests.
     */
    public function invoiceRequests()
    {
        return $this->hasMany(InvoiceRequest::class, 'payment_id');
    }

    /**
     * Each payment belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

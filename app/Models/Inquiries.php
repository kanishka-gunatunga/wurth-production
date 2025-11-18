<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiries extends Model
{
    use HasFactory;

    protected $table = 'inquiries';
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer', 'customer_id');
    }

    public function invoice()
    {
        // inquiries.invoice_number → invoices.id
        return $this->belongsTo(Invoices::class, 'invoice_number', 'invoice_or_cheque_no');
    }

    public function user()
    {
        // adm_id in inquiries → id in users
        return $this->belongsTo(User::class, 'adm_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiries extends Model
{
    use HasFactory;

    protected $table = 'inquiries';
    protected $primaryKey = 'id';

    public function invoice()
    {
        // invoice_number in inquiries → id in invoices
        return $this->belongsTo(Invoices::class, 'invoice_number', 'id');
    }

    public function customer()
    {
        // customer in inquiries → id in customers
        return $this->belongsTo(Customers::class, 'customer', 'id');
    }

    public function admin()
    {
        // adm_id in inquiries → id in users
        return $this->belongsTo(User::class, 'adm_id', 'id');
    }
}

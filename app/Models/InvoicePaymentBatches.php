<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentBatches extends Model
{
    use HasFactory;

    protected $table = 'invoice_payment_batches';
    protected $primaryKey = 'id';

}

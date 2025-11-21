<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    use HasFactory;

    protected $table = 'deposits';
    protected $primaryKey = 'id';


    /**
     * Automatically cast the receipts JSON into an array.
     */
    protected $casts = [
        'reciepts' => 'array',
    ];

    /**
     * Each deposit belongs to one ADM (user).
     * 
     * deposits.adm_id → users.id
     */
    public function adm()
    {
        return $this->belongsTo(User::class, 'adm_id', 'id');
    }

    /**
     * Get all invoice payments included in this deposit.
     * 
     * Since receipts is stored as a JSON array of IDs, 
     * we’ll create a dynamic accessor for convenience.
     */
    public function getInvoicePaymentsAttribute()
    {
        $receipts = $this->receipts ?? [];
        $ids = collect($receipts)->pluck('reciept_id')->toArray();

        return InvoicePayments::whereIn('id', $ids)->get();
    }
}

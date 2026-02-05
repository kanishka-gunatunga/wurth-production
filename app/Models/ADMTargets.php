<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ADMTargets extends Model
{
    use HasFactory;

    protected $table = 'adm_targets'; // your table name
    protected $primaryKey = 'id';

    protected $fillable = [
        'adm_no',
        'target',
        'year_and_month',
    ];

    /**
     * Each credit note belongs to a customer
     */
    public function adm()
    {
        return $this->belongsTo(UserDetails::class, 'adm_no', 'adm_number');
    }
}

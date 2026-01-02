<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Divisions extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'divisions';
    protected $primaryKey = 'id';


    /**
     * All user details under this division.
     */
    public function userDetails()
    {
        return $this->hasMany(UserDetails::class, 'division', 'id');
    }
public function heads()
{
    return $this->hasMany(UserDetails::class, 'division', 'id')
        ->whereHas('user', function ($q) {
            $q->where('user_role', 2)
              ->where('status', 'active');
        });
}

/**
 * Single division head (latest)
 */
public function head()
{
    return $this->heads()->latest()->first();
}

}

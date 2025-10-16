<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisions extends Model
{
    use HasFactory;

    protected $table = 'divisions';
    protected $primaryKey = 'id';


    /**
     * All user details under this division.
     */
    public function userDetails()
    {
        return $this->hasMany(UserDetails::class, 'division', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details';
    protected $primaryKey = 'id';

    /**
     * The main user record this detail belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The supervisor (another user) for this user detail.
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor', 'id');
    }

    /**
     * The division this user belongs to.
     */
    public function divisionData()
    {
        return $this->belongsTo(Divisions::class, 'division', 'id');
    }
}

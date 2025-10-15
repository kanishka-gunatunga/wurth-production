<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';
    protected $primaryKey = 'id';

    protected $casts = [
        'permissions' => 'array', // Automatically cast JSON to array
    ];

    /**
     * All users assigned to this role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'user_role', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'user_role',
        'status',
        'is_locked',
        'failed_attempts'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Each user has one user details record.
     */
    public function userDetails()
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    /**
     * Each user belongs to one role.
     */
    public function role()
    {
        return $this->belongsTo(RolePermissions::class, 'user_role', 'id');
    }

    /**
     * Each user can submit many inquiries.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiries::class, 'adm_id', 'id');
    }

     public function reminders()
    {
        return $this->hasMany(Reminders::class, 'sent_user_id', 'id');
    }

    /**
     * All users supervised by this user.
     */
    public function subordinates()
    {
        return $this->hasMany(UserDetails::class, 'supervisor', 'id');
    }

    public function paymentBatches()
{
    return $this->hasMany(InvoicePaymentBatches::class, 'adm_id', 'id');
}

public function activityLogs()
{
    return $this->hasMany(ActivtiyLog::class, 'user_id', 'id');
}

    public function payment()
{
    return $this->hasMany(InvoicePayments::class, 'adm_id', 'id');
}
}

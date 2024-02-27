<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleEnum;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Order\Contracts\ProvidesInvoiceInformation;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasName, ProvidesInvoiceInformation
{
    use HasApiTokens, HasFactory, Notifiable;

    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRoleEnum::class
    ];



    public function getFilamentName(): string
    {
        return "{$this->username}";
    }

    public function school(): HasOne
    {
        return $this->hasOne(School::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }

    public function getAvatarAttribute(): ?string
    {
        if ($this->role->isStudent()) {
            return $this->student->avatar;
        }
        if ($this->role->isSchool()) {
            return $this->school->avatar;
        }
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function isSchool(): bool
    {
        return $this->role->isSchool();
    }

    public function isInstructor(): bool
    {
        return $this->role->isInstructor();
    }

    public function isStudent(): bool
    {
        return $this->role->isStudent();
    }

    public function hasRole(string $role): bool
    {
        if ($role == 'admin') return $this->isAdmin();
        if ($role == 'instructor' || $role == 'teacher') return $this->isInstructor();
        if ($role == 'school') return $this->isSchool();
        if ($role == 'student') return $this->isStudent();

        return false;
    }

    public function suspend()
    {
        $this->is_suspended = true;
        $this->save();
    }

    public function unsuspend()
    {
        $this->is_suspended = false;
        $this->save();
    }

    public function getAvatar()
    {
        if ($this->isStudent())
            return $this->student->avatar_url;

        if ($this->isInstructor())
            return $this->instructor->avatar_url;
    }


    public function block()
    {
        $this->is_suspended = true;
        $this->save();
    }

    public function unblock()
    {
        $this->is_suspended = false;
        $this->save();
    }


    public function mollieCustomerFields()
    {
        return [
            'email' => $this->email,
            'name' => $this->username,
        ];
    }


    public function getInvoiceInformation()
    {
        return [$this->username, $this->email];
    }

    /**
     * Get additional information to be displayed on the invoice. Typically a note provided by the customer.
     *
     * @return string|null
     */
    public function getExtraBillingInformation()
    {
        return null;
    }
}

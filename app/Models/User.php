<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
        'staff_at',
    ];

    /**
     *  The attributes that should be appended.
     *
     * @var string[]
     */
    protected $appends = [
        'is_staff_member',
        'avatar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'staff_at' => 'datetime',
        ];
    }

    /**
     *  Scope a query to only include staff members.
     */
    public function scopeStaff($query): mixed
    {
        return $query->whereNotNull('staff_at');
    }

    /**
     *  Determine if the user is a staff member.
     */
    public function isStaff(): bool
    {
        return $this->staff_at !== null;
    }

    /**
     * An appended attribute to get staff member status.
     */
    protected function isStaffMember(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isStaff(),
        );
    }

    /**
     *  Promote the user to staff.
     */
    public function promoteToStaff(): void
    {
        if ($this->isStaff()) {
            return;
        }

        $this->forceFill([
            'staff_at' => Carbon::now(),
        ])->save();
    }

    /**
     *  The support requests that belong to the user.
     */
    public function supportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class, 'user_id');
    }

    /**
     * Get the Gravatar URL for the user.
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf(
                'https://www.gravatar.com/avatar/%s?s=%d',
                md5(strtolower(trim($this->email))),
                80
            )
        );
    }
}

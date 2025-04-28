<?php

namespace App\Models;

use Database\Factories\SupportRequestFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportRequest extends Model
{
    use HasUuids;

    public const STATUS_OPEN = 'open';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_CLOSED = 'closed';

    /** @use HasFactory<SupportRequestFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'staff_notes', // shown selectively
    ];

    protected $appends = [
        'formatted_category',
        'formatted_status',
        'formatted_preferred_date',
        'formatted_preferred_time',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'resolved_at' => 'datetime',
        'preferred_date' => 'date',
    ];

    /**
     * The user that owns the support request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user that is assigned to the support request.
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_user_id');
    }

    /**
     * Get the formatted category.
     */
    protected function formattedCategory(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->category) {
                    'general' => 'General',
                    'technical' => 'Technical',
                    'billing' => 'Billing',
                    default => 'Other',
                };
            }
        );
    }

    /**
     * Get the formatted status.
     */
    protected function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    self::STATUS_OPEN => 'Open',
                    self::STATUS_IN_PROGRESS => 'In Progress',
                    self::STATUS_CLOSED => 'Closed',
                    default => 'Unknown',
                };
            }
        );
    }

    /**
     * Get the formatted scheduled date.
     */
    protected function formattedPreferredDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->preferred_date ? $this->preferred_date->format('F d, Y') : null;
            }
        );
    }

    /**
     * Get the formatted scheduled time without seconds (12-hour format).
     */
    protected function formattedPreferredTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->preferred_time) {
                    return null;
                }

                $parts = explode(':', $this->preferred_time);
                $hour = (int) $parts[0];
                $minute = $parts[1];

                $suffix = $hour >= 12 ? 'PM' : 'AM';
                $hour %= 12;
                if ($hour === 0) {
                    $hour = 12;
                }

                return sprintf('%d:%s %s', $hour, $minute, $suffix);
            }
        );
    }
}

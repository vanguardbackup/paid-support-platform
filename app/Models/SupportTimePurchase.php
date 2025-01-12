<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTimePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity',
        'support_type',
        'details',
        'payment_id',
        'amount',
        'expired_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the user that owns the support time purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the purchase is expired.
     */
    public function isExpired(): bool
    {
        return $this->expired_at !== null;
    }

    /**
     * Check if the purchase is active.
     */
    public function isActive(): bool
    {
        return ! $this->isExpired();
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '£' . number_format($this->amount, 2);
    }

    /**
     * Get the formatted support type.
     */
    public function getFormattedSupportTypeAttribute(): string
    {
        return ucfirst($this->support_type);
    }

    /**
     * Scope a query to only include active purchases.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('expired_at');
    }

    /**
     * Scope a query to only include expired purchases.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expired_at');
    }

    /**
     * Scope a query to only include purchases of a given type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('support_type', $type);
    }

    /**
     * Expire the purchase.
     */
    public function expire(): void
    {
        $this->update(['expired_at' => now(), 'quantity' => 0]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Reservation
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string|null $reservation_started_at
 * @property int|null $number_of_days
 * @property int|null $amount
 * @property string $box_identifier
 * @property mixed|null $transaction
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereBoxIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereReservationStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 * @property string|null $unique_identifier
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUniqueIdentifier($value)
 * @property string|null $active_from
 * @property string|null $active_until
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereActiveFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereActiveUntil($value)
 */
class Reservation extends Model
{
    use HasFactory;

    protected $dates = ['reservation_started_at', 'active_from', 'active_until'];

    protected $casts = [
        'transaction' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function (self $reservation) {
            $reservation->unique_identifier = \Str::random(20);
        });
    }

    public function identifier(): string
    {
        return $this->id.'-'.$this->unique_identifier;
    }
}

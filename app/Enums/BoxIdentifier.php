<?php

namespace App\Enums;

use App\Models\Reservation;
use JetBrains\PhpStorm\Pure;
use Livewire\Wireable;
use Spatie\Enum\Enum;

/**
 * @method static self big_box()
 * @method static self medium_box_1()
 * @method static self medium_box_2()
 * @method static self small_box_1()
 * @method static self small_box_2()
 * @method static self small_box_3()
 */
class BoxIdentifier extends Enum implements Wireable
{
    #[Pure]
    public function price(): float
    {
        if(\Str::startsWith($this->value, 'big')) {
            return 0.2;
        } else if(\Str::startsWith($this->value, 'medium')) {
            return 0.1;
        } else {
            return 0.05;
        }
    }

    public function toLivewire(): string
    {
        return $this->value;
    }

    public static function fromLivewire($value): static
    {
        return static::from($value);
    }

    public function canBeReserved(): bool
    {
        $count = Reservation::query()
            ->whereStatus(ReservationStatus::confirmed()->value)
            ->where('box_identifier', $this->value)
            ->where('active_until', '>=', now())
            ->count();
        return $count == 0;
    }
}

<?php

namespace App\Http\Livewire;

use App\Enums\BoxIdentifier;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Livewire\Component;

class ReserveBoxButton extends Component
{
    public bool $modalIsOpen = false;
    protected $transactionObject = null;

    public BoxIdentifier $boxIdentifier;
    public Reservation $reservation;

    public bool $confirmed = false;
    public int $number_of_days = 1;

    public $transactionDescription = null;

    protected $rules = ['number_of_days' => 'required|integer|min:1'];

    public function render()
    {
        return view('livewire.reserve-box-button', [
            'transaction' => $this->transactionObject,
            'box' => $this->boxIdentifier,
        ]);
    }

    public function mount($box)
    {
        $this->boxIdentifier = $box;
    }

    public function openModal()
    {
        $this->modalIsOpen = true;
    }

    public function checkForTransaction()
    {
        if(!$this->modalIsOpen) {
            return;
        }
        $address  = config('payforspace.elrond_api') . 'address/' . config('payforspace.elrond_wallet_address') . '/transactions';
        $response = \Http::get($address);

        $transactions = collect($response['data']['transactions']);

        $theOne = $transactions->first(fn($el) => $el['data'] && str_contains(base64_decode($el['data']), $this->transactionDescription));

        if ($theOne) {
            $price = bcdiv($theOne['value'], 1000000000000000000, 2);
            if ($price == bcdiv($this->reservation->amount, 100, 2) && $theOne['receiver'] == config('payforspace.elrond_wallet_address')) {
                $this->reservation->transaction = $theOne;
                $this->reservation->status = ReservationStatus::confirmed()->value;
                $this->reservation->active_from = now();
                $this->reservation->active_until = now()->addDays($this->reservation->number_of_days);
                $this->reservation->save();
                $this->transactionObject = $theOne;
            }
        }
    }

    public function amountToPay(): float
    {
        return bcmul($this->number_of_days, $this->boxIdentifier->price(), 2);
    }

    public function updatedNumberOfDays()
    {
        $this->validate();
    }

    public function confirm()
    {
        $this->validate();

        $reservation = new Reservation([
            'reservation_started_at' => now(),
            'status' => ReservationStatus::pending()->value,
            'number_of_days' => $this->number_of_days,
            'amount' => bcmul($this->amountToPay(), 100),
            'box_identifier' => $this->boxIdentifier->value,
        ]);

        auth()->user()->reservations()->save($reservation);

        $this->transactionDescription = $reservation->identifier();

        $this->confirmed = true;
        $this->reservation = $reservation;
    }
}

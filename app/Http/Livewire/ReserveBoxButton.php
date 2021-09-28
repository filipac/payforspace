<?php

namespace App\Http\Livewire;

use App\Enums\BoxIdentifier;
use App\Enums\CheckingMethod;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Transaction;
use Illuminate\Support\Arr;
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
            'box'         => $this->boxIdentifier,
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
        if (!$this->modalIsOpen) {
            return;
        }

        $theOne = $this->getTheOne();

        if ($theOne) {
            $price    = bcdiv($theOne['value'], 1000000000000000000, 2);
            $receiver = config('payforspace.checking_method')
            == CheckingMethod::local()->value ? Arr::get($theOne->object, 'transaction.receiver') : $theOne['receiver'];
            if ($price == bcdiv($this->reservation->amount, 100, 2) && $receiver == config('payforspace.elrond_wallet_address')) {
                $this->reservation->transaction  = $theOne;
                $this->reservation->status       = ReservationStatus::confirmed()->value;
                $this->reservation->active_from  = now();
                $this->reservation->active_until = now()->addDays($this->reservation->number_of_days);
                $this->reservation->save();
                $this->transactionObject = config('payforspace.checking_method') == CheckingMethod::local()->value ? $theOne->object : $theOne;
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
            'status'                 => ReservationStatus::pending()->value,
            'number_of_days'         => $this->number_of_days,
            'amount'                 => bcmul($this->amountToPay(), 100),
            'box_identifier'         => $this->boxIdentifier->value,
        ]);

        auth()->user()->reservations()->save($reservation);

        $this->transactionDescription = $reservation->identifier();

        $this->confirmed   = true;
        $this->reservation = $reservation;
    }

    /**
     * @return mixed
     */
    protected function getTheOne(): mixed
    {
        if (config('payforspace.checking_method') === CheckingMethod::local()->value) {
            return $this->getLocalTransaction();
        }
        return $this->getGatewayTransaction();
    }

    protected function getLocalTransaction(): null|Transaction
    {
        return Transaction::query()
            ->where('data', 'like', $this->transactionDescription . '%')
            ->first();
    }

    /**
     * @return mixed
     */
    protected function getGatewayTransaction(): mixed
    {
        $address  = config('payforspace.elrond_gateway') . 'address/' . config('payforspace.elrond_wallet_address') . '/transactions';
        $response = \Http::get($address);

        $transactions = collect($response['data']['transactions']);

        $theOne = $transactions->first(fn($el) => $el['data'] && str_contains(base64_decode($el['data']), $this->transactionDescription));
        return $theOne;
    }
}

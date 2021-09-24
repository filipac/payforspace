<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DummyBuyButton extends Component
{
    public bool $modalIsOpen = false;
    protected $transactionObject = null;

    public $transactionDescription = '123';

    public function render()
    {
        return view('livewire.dummy-buy-button', [
            'transaction' => $this->transactionObject,
        ]);
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
//        $this->transactionObject = $response->json();

        $transactions = collect($response['data']['transactions']);

        $theOne = $transactions->first(fn($el) => $el['data'] && base64_decode($el['data']) == $this->transactionDescription);
        if ($theOne) {
            $price = bcdiv($theOne['value'], 1000000000000000000, 2);
            if ($price == config('payforspace.price') && $theOne['receiver'] == config('payforspace.elrond_wallet_address')) {
                $this->transactionObject = $theOne;
                ray($theOne);
            }
        }
    }
}

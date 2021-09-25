<div>
    <div class="w-full flex justify-center">
        @if($this->boxIdentifier->canBeReserved())
        <a href="#" wire:click.prevent="openModal()" class="block bg-yellow-200 p-4 rounded shadow-md min-w-1/4">Reserve
            this spot</a>
        @endif
    </div>
    <x-jet-dialog-modal wire:model="modalIsOpen">
        <x-slot name="title">
            Buy a spot
        </x-slot>

        <x-slot name="content">

            @unless($transaction)
                @if($confirmed)
                    <div wire:poll.2000ms="checkForTransaction">
                        <p>In order to buy this spot, send {{ $this->amountToPay() }} EGLD to the following
                            address. Please make sure to include the identifier below in the transaction details and
                            send only the exact amount.</p>
                        <p class="w-full text-center font-bold py-4">{{config('payforspace.elrond_wallet_address')}}</p>
                        <p class="w-full text-center py-1"><span class="font-bold">Transaction details/identifier</span>: {{$reservation->identifier()}}
                        </p>
                        <div class="w-full flex justify-center py-4">
                            {!! QrCode::size(200)->generate(config('payforspace.price')); !!}
                        </div>
                        <div class="w-full flex justify-center py-4">
                            @include('livewire.spinner')
                        </div>
                    </div>
                @else
                    <p>
                        Reserving this space costs {{ $box->price() }} per day.
                    </p>
                    <p>
                        Please specify the number of days you want to reserve this space.
                    </p>
                    <div>
                        <input type="number" wire:model="number_of_days">
                        @error('number_of_days') <span class="error">{{ $message }}</span> @enderror

                    </div>
                    @unless($errors->hasAny('number_of_days'))
                        <div>
                            Total amount to pay: {{ $this->amountToPay() }} EGLD
                        </div>
                    @endunless
                    <div>
                        <button wire:click.prevent="confirm"
                                class="p-4 mt-4 rounded shadow-md {{$errors->hasAny('number_of_days') ? 'bg-gray-200 cursor-not-allowed' : 'bg-yellow-200'}}">
                            Confirm and
                            pay
                        </button>
                    </div>
                @endif
            @else
                <pre class="overflow-x-scroll">{!! var_dump($transaction) !!}</pre>
            @endunless
        </x-slot>

        <x-slot name="footer">
            {{--            <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">--}}
            {{--                {{ __('Cancel') }}--}}
            {{--            </x-jet-secondary-button>--}}
        </x-slot>
    </x-jet-dialog-modal>
</div>

<div>
    <div class="w-full flex justify-center">
        <a href="#" wire:click.prevent="openModal()" class="block bg-yellow-200 p-4 rounded shadow-md w-1/4">Reserve
            this spot</a>
    </div>
    <x-jet-dialog-modal wire:model="modalIsOpen">
        <x-slot name="title">
            Buy a spot
        </x-slot>

        <x-slot name="content">

            @unless($transaction)
                <div wire:poll.2000ms="checkForTransaction">
                    <p>In order to buy this spot, send {{config('payforspace.price')}} EGLD to the following
                        address:</p>
                    <p class="w-full text-center font-bold py-4">{{config('payforspace.elrond_wallet_address')}}</p>
                    <div class="w-full flex justify-center py-4">
                        {!! QrCode::size(200)->generate(config('payforspace.price')); !!}
                    </div>
                    <div class="w-full flex justify-center py-4">
                        @include('livewire.spinner')
                    </div>
                </div>
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

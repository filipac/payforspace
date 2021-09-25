<x-guest-layout>
<div class="min-h-screen bg-yellow-200 w-full flex flex-col justify-center items-center">
    <div class="text-6xl font-bold my-12">PayFor.Space</div>
    <div class="bg-white w-1/2 p-4 rounded shadow-md">
        @auth
            <livewire:reserve-box-button :box="\App\Enums\BoxIdentifier::big_box()" />
        @endauth
    </div>
    <div class="mt-6 grid grid-cols-2 gap-2 w-1/2">
        <div class="bg-white w-full p-4 rounded shadow-md">
            <livewire:reserve-box-button :box="\App\Enums\BoxIdentifier::medium_box_1()" />
        </div>
        <div class="bg-white w-full p-4 rounded shadow-md">
            <livewire:reserve-box-button :box="\App\Enums\BoxIdentifier::medium_box_2()" />
        </div>
    </div>
    <div class="mt-6 grid grid-cols-3 gap-2 w-1/2">
        <div class="bg-white w-full p-4 rounded shadow-md">
            <livewire:reserve-box-button :box="\App\Enums\BoxIdentifier::small_box_1()" />
        </div>
        <div class="bg-white w-full p-4 rounded shadow-md">
            <livewire:reserve-box-button :box="\App\Enums\BoxIdentifier::small_box_2()" />
        </div>
        <div class="bg-white w-full p-4 rounded shadow-md">
            <livewire:reserve-box-button :box="\App\Enums\BoxIdentifier::small_box_3()" />
        </div>
    </div>
</div>
</x-guest-layout>

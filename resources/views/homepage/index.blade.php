<x-guest-layout>
<div class="min-h-screen bg-yellow-200 w-full flex flex-col justify-center items-center">
    <div class="text-6xl font-bold my-12">PayFor.Space</div>
    <div class="bg-white w-1/2 p-16 rounded shadow-md">
        <div>
            big ad
        </div>

        @auth
            <livewire:dummy-buy-button />
        @endauth
    </div>
    <div class="mt-6 grid grid-cols-2 gap-2 w-1/2">
        <div class="bg-white w-full p-16 rounded shadow-md">
            medium ad
        </div>
        <div class="bg-white w-full p-16 rounded shadow-md">
            medium ad
        </div>
    </div>
    <div class="mt-6 grid grid-cols-3 gap-2 w-1/2">
        <div class="bg-white w-full p-16 rounded shadow-md">
            small ad
        </div>
        <div class="bg-white w-full p-16 rounded shadow-md">
            small ad
        </div>
        <div class="bg-white w-full p-16 rounded shadow-md">
            small ad
        </div>
    </div>
</div>
</x-guest-layout>

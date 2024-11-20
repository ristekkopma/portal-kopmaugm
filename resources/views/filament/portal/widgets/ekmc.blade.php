@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-member-widget">
    <div class="relative bg-cover bg-center rounded-lg "
        style="height: 0; padding-top: 56.25%; background-image: url('{{ asset('storage/images/E-KMC.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg"></div>

        <div class="absolute inset-x-0 bottom-0 z-10 p-6">
            <!-- Nama Member -->
            <h2 class="text-2xl sm:text-2xl font-bold text-white">
                {{ $user->name }}
            </h2>

            <!-- Kode Member -->
            <p class="text-2xl sm:text-2xl text-gray-200">
                {{ $user->member->code }}
            </p>
        </div>
    </div>
</x-filament-widgets::widget>

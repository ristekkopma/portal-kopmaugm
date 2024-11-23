<x-filament-widgets::widget>
    @if ($events->count() > 0)
        <div class="pb-4 text-center text-xl">{{ __('Upcoming events') }}</div>
    @endif
    @foreach ($events as $event)
        <x-filament::section>
            <div class="relative bg-cover bg-center rounded-lg "
                style="height: 0; padding-top: 56.25%; background-image: url('{{ asset('storage/' . $event->image) }}');">
                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg"></div>
            </div>
            <div class="inset-0 flex items-center justify-center pt-4">
                <div class="z-10 text-center">
                    <!-- Nama Member -->
                    <h2 class="text-xl sm:text-3xl font-bold text-white">
                        {{ $event->title }}
                    </h2>

                    <!-- Kode Member -->
                    <p class="text-base sm:text-2xl text-gray-200">
                        {{ __('Registration closed at :date', ['date' => $event->closed_at->format('d M Y')]) }}
                    </p>
                </div>
            </div>
            <div class="inset-0 flex items-center justify-center pt-4">
                <x-filament::button class="w-1/2 mt-4">
                    <a href="{{ $event->url }}" target="_blank">
                        {{ __('Join now') }}
                    </a>
                </x-filament::button>
            </div>
        </x-filament::section>
    @endforeach
</x-filament-widgets::widget>

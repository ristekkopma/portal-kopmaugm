@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col sm:flex-row items-center gap-x-3 gap-y-4 sm:gap-y-0">
            <div class="flex-1 text-center md:text-start">
                <h1 class="text-3xl font-bold">{{ __('Welcome') }} {{ $user->name }}!</h1>
                <p class="mt-4">
                    {{ __('Please click the button to continue registration') }}
                </p>
                <div class="pt-4">
                    <x-filament::button class="w-auto mt-4">
                        <a href="{{ route('filament.portal.auth.profile') }}">{{ __('Complete your profile data') }}</a>
                    </x-filament::button>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

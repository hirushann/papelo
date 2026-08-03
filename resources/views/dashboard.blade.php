<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->is_admin)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("You're logged in as Admin!") }}
                        <div class="mt-4">
                            <flux:button href="{{ route('admin.questions') }}" variant="primary" wire:navigate>
                                Go to Question Manager
                            </flux:button>
                        </div>
                    </div>
                </div>
            @else
                <livewire:student-dashboard />
            @endif
        </div>
    </div>
</x-app-layout>

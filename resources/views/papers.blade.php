<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exam Papers') }}
        </h2>
    </x-slot>

    <livewire:paper-catalog />
</x-app-layout>

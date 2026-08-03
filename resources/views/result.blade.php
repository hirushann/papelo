<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Result Summary: ') }} {{ $attempt->paper->title }}
        </h2>
    </x-slot>

    <livewire:result-summary :attempt="$attempt" />
</x-app-layout>

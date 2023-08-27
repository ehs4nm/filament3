<x-filament-panels::page.simple>
    @if (filament()->hasLogin())
        <x-slot name="subheading" class="font-bold tracking-tight text-center text-2xl mb-4">
            {{ $this->loginAction }}
        </x-slot>
    @endif

    <x-filament-panels::form wire:submit="request">
            {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <style>
        #data\.mobile, #data\.verify_code{
            text-align: center;
            font-size: xx-large;
        }
    </style>
</x-filament-panels::page.simple>
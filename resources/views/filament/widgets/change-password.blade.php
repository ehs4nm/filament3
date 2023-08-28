<x-filament-widgets::widget>
    <x-filament::section>
        <div class="mt-4">
            <p class="underline flex">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6" class="h-6 mx-3">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <a href="{{route('filament.admin.auth.profile')}}">
                    از اینجا رمزعبور خود را تغییر دهید.
                </a>
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

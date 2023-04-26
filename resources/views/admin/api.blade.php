<x-app-layout>
    <form method="post" action="{{ route('connectAPI', 1) }}" class="mt-6 space-y-6">
        @csrf
        <div>
            <div class="flex gap-4">
                <div>
                    <x-input-label for="api-key" :value="__('API-sleutelnummer')" />
                    <x-text-input id="api-key" name="api-key" type="text" class="mt-1 block w-full" autocomplete="api-key" />
                    <x-input-error class="mt-2" :messages="$errors->get('api-key')" />
                </div>
            </div>

            <div class="flex gap-4">
                <div>
                    <x-input-label for="rfid-token" :value="__('RFID Token')" />
                    <x-text-input id="rfid-token" name="rfid-token" type="text" class="mt-1 block w-full" autocomplete="rfid-token" />
                    <x-input-error class="mt-2" :messages="$errors->get('rfid-token')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Verbinden') }}</x-primary-button>
        </div>
    </form>
</x-app-layout>

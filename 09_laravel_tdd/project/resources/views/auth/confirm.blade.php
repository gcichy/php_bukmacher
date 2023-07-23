<x-guest-layout>
    <p>Wprowadź kod potwierdzający, który został wysłany na twojego maila.</p>
    <form method="POST" action="{{ route('confirm.check') }}">
        @csrf
        <!-- User_id -->
        @if(isset($user))
            <div>
                <input id="person_number" name="person_number" type="hidden" value="{{$user->person_number}}">
            </div>
        @endif
        <!-- Token -->
        <div>
            <x-input-label for="token" :value="__('Otrzymany kod')" />
            <x-text-input id="token" class="block mt-1 w-full" type="text" name="token" />
            <x-input-error :messages="$errors->get('token')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Potwierdź rejestrację') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

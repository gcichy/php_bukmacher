<x-guest-layout>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        @if(isset($email))
            <div>
                <p>Kod zmiany hasła został wysłany na email: {{$email}}</p>
            </div>
        @elseif(isset(($request->session()->get('_old_input'))['email']))
            <div>
                <p>Kod zmiany hasła został wysłany na email: {{($request->session()->get('_old_input'))['email']}}</p>
            </div>
        @endif
        <!-- Password Reset Token -->
        <div>
            <x-input-label for="token" :value="__('Otrzymany kod')" />
            <x-text-input id="token" class="block mt-1 w-full" type="text" name="token" />
            <x-input-error :messages="$errors->get('token')" class="mt-2" />
        </div>

{{--        <input type="hidden" name="token" value="{{ $request->route('token') }}">--}}

        <!-- Email Address -->
        <div>
            @if(isset($email))
                <input id="email" name="email" type="hidden" value="{{$email}}"/>
            @else
                <input id="email" name="email" type="hidden" value="{{old('email')}}"/>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Hasło')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"  />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Potwierdź hasło')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation"  />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Zresetuj Hasło') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

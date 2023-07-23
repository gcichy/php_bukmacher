@props(['messages'])
@if (isset($messages))
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            @if($message == 'Pole name jest wymagane')
                <li class='error'>{{'Pole Imie jest wymagane'}}</li>
            @elseif($message == 'Pole surename jest wymagane')
                <li class='error'>{{'Pole Nazwisko jest wymagane'}}</li>
            @elseif($message == 'Pole nickname jest wymagane')
                <li class='error'>{{'Pole Nazwa użytkownika jest wymagane'}}</li>
            @elseif($message == 'Pole password jest wymagane')
                <li class='error'>{{'Pole Hasło jest wymagane'}}</li>
            @elseif($message == 'Pole repeat password jest wymagane')
                <li class='error'>{{'Pole Powtórz hasło jest wymagane'}}</li>
            @elseif($message == 'Pole phone number jest wymagane')
                <li class='error'>{{'Pole Numer telefonu jest wymagane'}}</li>
            @elseif($message == 'Pole person number jest wymagane')
                <li class='error'>{{'Pole Pesel jest wymagane'}}</li>
            @elseif($message == 'Pole terms jest wymagane')
                <li class='error'>{{'Wymagana jest akceptacja regulaminu'}}</li>
            @elseif($message == 'The email format is invalid.')
                <li class='error'>{{'Email jest niepoprawny'}}</li>
            @elseif($message == 'The password format is invalid.' || $message == 'password musi mieć przynajmniej 8 znaków.')
                <li class='error'>{{'Hasło musi zawierać przynajmniej 8 znaków, wielką literę, małą literę, cyfrę oraz jeden znak specjalny'}}</li>
            @elseif($message == 'Not 18 years old')
                <li class='error'>{{'Użytkownik musi mieć ukończone 18 lat'}}</li>
            @elseif($message == 'The repeat password and password must match.')
                <li class='error'>{{'Hasła muszą być identyczne'}}</li>
            @elseif($message == 'eleven characters required')
                <li class='error'>{{'Pesel musi mieć 11 cyfr'}}</li>
            @elseif($message == 'not numeric values')
                <li class='error'>{{'Podaj PESEL'}}</li>
            @elseif($message == 'Pole token jest wymagane')
                <li class="error">{{'Pole Otrzymany kod jest wymagane'}}</li>
            @elseif($message == 'Pole password confirmation jest wymagane')
                <li class="error">{{'Pole Potwierdź hasło jest wymagane'}}</li>
            @elseif($message == 'Pole password różni się od pola Potwierdź password.')
                <li class="error">{{'Pole Hasło różni się od pola Potwierdź hasło.'}}</li>
            @elseif($message == 'The amount must be a number.')
                <li class="error">{{'Podaj właściwą kwotę.'}}</li>
            @elseif($message == 'Pole amount jest wymagane')
                <li class="error">{{'Należy podac kwotę.'}}</li>
            @else
                <li class="error">{{$message}}</li>
            @endif

        @endforeach
    </ul>
@endif

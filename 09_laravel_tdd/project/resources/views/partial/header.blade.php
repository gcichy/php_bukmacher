<script>
    let isDisc = false;
    function scrollToBottom(id) {
        let element = document.getElementById(id);
        element.scrollIntoView(false);
    }
    function showDisciplines() {
        let menu = document.getElementById("DisciplineMenu")
        if (isDisc) {
            menu.style.display = "none";
            isDisc = false;
        }
        else {
            menu.style.display = "grid";
            isDisc = true;
        }
    }
</script>

<style>
    .big-letters {
        font-size: 30px;
    }
    .medium-letters {
        font-size: 20px;
    }
    .small-letters {
        font-size: 10px;
    }
    .navbar {
        margin-left: auto;
        margin-right: auto;
        overflow: hidden;
        background-color: whitesmoke;
        position: fixed; /* Set the navbar to fixed position */
        top: 0; /* Position the navbar at the top of the page */
        width: 99%;
        z-index: 1;
    }
    #MainLogoImg {
        width: 90px;
    }

    #user_icon_image {
        width: 90px;
    }

    #DisciplineMenu {
        position: fixed;
        top: 160px;
        left: 720px;
        width: 400px;
        height: 80px;
        display: none;
        grid-template-columns: 33% 33% 33%;
        z-index: 2;
        border: 3px solid brown;
        border-radius: 3px;
        padding: 4px;
        background-color: whitesmoke;
    }
    #DisciplineMenu > a {
        font-size: 20px;
        text-align: center;
    }
</style>
<div id="DisciplineMenu">
    <a href="{{url('/bets/football')}}">Piłka Nożna</a>
    <a href="{{url('/bets/volleyball')}}">Siatkówka</a>
    <a href="{{url('/bets/dart')}}">Dart</a>
    <a href="{{url('/bets/handball')}}">Piłka Ręczna</a>
    <a href="{{url('/bets/basketball')}}">Koszykówka</a>
    <a href="{{url('/bets/esport')}}">E-sport</a>
</div>
<div class="navbar">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a id="UserIconA" href="{{ url('/account') }}"><img id="user_icon_image" src="{{ asset('img/userIcon.png') }}" alt="user icon"></a>
                <p class="text-sm text-gray-700 dark:text-gray-500 medium-letters">Stan konta: {{$user->deposit}}</p>
                <p class="text-sm text-gray-700 dark:text-gray-500 medium-letters">Konto {{$user->premium == 1 ? __('Premium') : __('Standardowe')}}</p>
                @if(isset($premium_expire))
                    <p class="text-sm text-gray-700 dark:text-gray-500 medium-letters">{{$premium_expire}}</p>
                @endif
                {{--                <form method="POST" action="{{'/logout'}}">--}}
{{--                    @csrf--}}
{{--                    <input type="hidden" name="_token" value="bUkoABWBCFjT1rKLpZcT4pcsEPvfdi3gw7amOXkS">--}}
{{--                    <a class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" href="{{'/logout'}}" onclick="event.preventDefault();--}}
{{--                                                this.closest('form').submit();">Log Out</a>--}}
{{--                </form>--}}
                <form method="POST" action="{{route('logout')}}">
                    @csrf
                    <x-primary-button class="ml-3">
                        {{ __('Wyloguj się') }}
                    </x-primary-button>
                </form>
            @else
                <a id="login_link" href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline medium-letters">Zaloguj się</a>

                @if (Route::has('register'))
                    <a id="register_link" href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline medium-letters">Zarejestruj się</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="hidden fixed top-0 left-0 px-6 py-4 sm:block">
        <img id="MainLogoImg" src="{{asset('img/KGKMBets.svg')}} " alt="Logo">
    </div>

    <br><br><br><br>
    <div class="hidden fixed sm:block px-6 py-5"  style="z-index: 1; border-bottom: 2px solid brown; background-color: whitesmoke; margin-left: 4px">
        <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 15px;" href="{{ url('/home') }}">Strona główna</a>
        <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 15px;" href="{{ url('/bets') }}">Zakłady bukmacherskie</a>
        <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 15px;" href="{{ url('/specialoffers') }}">Zakłady specjalne</a>
        <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 15px;" href="#" onclick="showDisciplines()">Dyscypliny</a>
        <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 15px;" href="{{ url('/results') }}">Wyniki</a>

        @auth
            <?php if (isset($user) && $user->premium) {?>
            <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 10px;" href="{{ url('/livebets') }}">Zakłady live</a>
            <a class="text-sm text-gray-700 dark:text-gray-500 big-letters" style="margin-right: 10px;" href="{{ url('/scratchcard') }}">Zdrapka</a>
            <?php } ?>
        @endauth
    </div>
    <br><br>
        @if (count(explode('/', substr(url()->current(), 7))) == 1)
            <br>
            <div class="hidden fixed sm:block" style="margin-left: 10%;">
                <a class="text-sm text-gray-700 dark:text-gray-500 medium-letters underline" style="margin-right: 10px;" onclick="scrollToBottom('us')">O nas</a>
                <a class="text-sm text-gray-700 dark:text-gray-500 medium-letters underline" style="margin-right: 10px;" onclick="scrollToBottom('contact')">Kontakt</a>
                <a class="text-sm text-gray-700 dark:text-gray-500 medium-letters underline" style="margin-right: 10px;" onclick="scrollToBottom('rules')">Regulamin</a>
            </div>
        @endif
</div>

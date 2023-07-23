<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KGKM Bets</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->

        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--tw-bg-opacity: 1;background-color:rgb(255 255 255 / var(--tw-bg-opacity))}.bg-gray-100{--tw-bg-opacity: 1;background-color:rgb(243 244 246 / var(--tw-bg-opacity))}.border-gray-200{--tw-border-opacity: 1;border-color:rgb(229 231 235 / var(--tw-border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{--tw-shadow: 0 1px 3px 0 rgb(0 0 0 / .1), 0 1px 2px -1px rgb(0 0 0 / .1);--tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000),var(--tw-ring-shadow, 0 0 #0000),var(--tw-shadow)}.text-center{text-align:center}.text-gray-200{--tw-text-opacity: 1;color:rgb(229 231 235 / var(--tw-text-opacity))}.text-gray-300{--tw-text-opacity: 1;color:rgb(209 213 219 / var(--tw-text-opacity))}.text-gray-400{--tw-text-opacity: 1;color:rgb(156 163 175 / var(--tw-text-opacity))}.text-gray-500{--tw-text-opacity: 1;color:rgb(107 114 128 / var(--tw-text-opacity))}.text-gray-600{--tw-text-opacity: 1;color:rgb(75 85 99 / var(--tw-text-opacity))}.text-gray-700{--tw-text-opacity: 1;color:rgb(55 65 81 / var(--tw-text-opacity))}.text-gray-900{--tw-text-opacity: 1;color:rgb(17 24 39 / var(--tw-text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--tw-bg-opacity: 1;background-color:rgb(31 41 55 / var(--tw-bg-opacity))}.dark\:bg-gray-900{--tw-bg-opacity: 1;background-color:rgb(17 24 39 / var(--tw-bg-opacity))}.dark\:border-gray-700{--tw-border-opacity: 1;border-color:rgb(55 65 81 / var(--tw-border-opacity))}.dark\:text-white{--tw-text-opacity: 1;color:rgb(255 255 255 / var(--tw-text-opacity))}.dark\:text-gray-400{--tw-text-opacity: 1;color:rgb(156 163 175 / var(--tw-text-opacity))}.dark\:text-gray-500{--tw-text-opacity: 1;color:rgb(107 114 128 / var(--tw-text-opacity))}}
            .small-icon {
                width: 10%;
                margin-right: 10px;
            }
            .ads_img {
                width: 100px;
            }
        </style>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased" style="background: whitesmoke">
        @include("partial.header")
        <div class="relative justify-center" style="justify-content: center; width:50%; margin: 200px auto auto;">
            <div style="text-align: center">
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <h3 style="text-align: left">KGKM Bets</h3>
                <p style="text-align: justify">KGKM Bets to organizacja pełna pasji i miłości do sportu. Założona w roku 2022 przez czworo ambitnych studentów, dzisiaj dostarcza
                emocji i wrażeń całym rzeszom użytkowników na terenie całej Polski. Skromne początki KGKM sięgają zajęć z zaawansowanego programowania w PHP, jednak
                nadzwyczajna realizacja tematu wraz z odpowiednim wybadaniem rynku zakładowego szybko doprowadziły do rosnącej popularności serwisu KGKM Bets, a co
                za tym idzie, sławy i chwały dla jej twórców.</p>
                <section id="us"></section>
            </div>
            <div style="text-align: justify">
                <h3 style="text-align: left">Kontakt</h3>
                <p>W razie pytań, problemów czy wątpliwości prosimy o kontakt.</p>
                <p>Adres biura:</p>
                <ul>
                    <li>w Krakowie - ul. Witolda Budryka 3, pokój 409B, 30-072 Kraków</li>
                    <li>w Warszawie - ul. Żwirka i Wigury 517, piętro 74, pokój 23, 02-095 Warszawa</li>
                    <li>w Sopocie - ul. Bohaterów Monte Cassino 53, pokój 2, 81-767 Sopot</li>
                </ul>
                <p>Numer telefonu do kancelarii: +48 604 206 944.</p>
                <p>Kancelaria czynna w godzinach 9:00 - 15:00 w dni robocze.</p>

                <br>
                <div>
                    <a href="https://www.facebook.com"><img class="small-icon" src="{{ asset('img/facebook.png') }}" alt="facebook icon"></a>
                    <a href="https://www.twitter.com"><img class="small-icon" src="{{ asset('img/twitter.png') }}" alt="twitter icon"></a>
                    <a href="https://www.instagram.com"><img class="small-icon" src="{{ asset('img/instagram.png') }}" alt="instagram icon"></a>
                    <a href="https://www.github.com"><img class="small-icon" src="{{ asset('img/github.png') }}" alt="github icon"></a>
                </div>
                <section id="contact"></section>
            </div>
            <div style="text-align: left">
                <h3 style="text-align: left">Regulamin</h3>
                <p style="margin-left: 20px;">Udział w grze jest dozwolony jedynie po zaakceptowaniu poniższych zasad:</p>
                <ol>
                    <li>Obowiązaują zasady fair play</li>
                    <li>Gracz musi posiadać ukończone 18 lat</li>
                    <li>Podmiotem prawnym KGKM Bets jest KGKM</li>
                    <li>Wszelkie środki wpłacone na konto stają się własnością KGKM</li>
                    <li>W przypadku podejrzanych działań danego konta KGKM zastrzega sobie prawo do zablokowania go jeżeli wysłane wpierw ostrzeżenia nie przyniosą efektóœ.</li>
                    <li>{{url()->current()}}</li>
                </ol>
                <section id="rules"></section>
            </div>
            @auth
                @if(!$user->premium)
                    <div id="ads_1"><img class="ads_img" src="{{ asset('img/ads/A'.mt_rand(0, 10).'.png') }}" alt="Ads icon"></div>
                    <div id="ads_2"><img class="ads_img" src="{{ asset('img/ads/A'.mt_rand(0, 10).'.png') }}" alt="Ads icon"></div>
                @endif
            @else
                <div class="ads_1"><img class="ads_img" src="{{ asset('img/ads/A'.mt_rand(0, 10).'.png') }}" alt="Ads icon"></div>
                <div class="ads_2"><img class="ads_img" src="{{ asset('img/ads/A'.mt_rand(0, 10).'.png') }}" alt="Ads icon"></div>
                <div class="ads_3"><img class="ads_img" src="{{ asset('img/ads/A'.mt_rand(0, 10).'.png') }}" alt="Ads icon"></div>
            @endauth
        </div>
    </body>
</html>

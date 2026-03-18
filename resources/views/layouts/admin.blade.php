<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bluedactyl - @yield('title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover"
        name="viewport">
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Bluedactyl" />
    <link rel="manifest" href="/favicons/site.webmanifest" />

    <meta name="theme-color" content="#000000">
    <meta name="darkreader-lock">

    @include('layouts.scripts')

    @section('scripts')
        {!! Theme::css('vendor/select2/select2.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/sweetalert/sweetalert.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/animate/animate.min.css?t={cache-version}') !!}
        {!! Theme::css('css/pterodactyl.css?t={cache-version}') !!}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        {{-- daisyUI 5 & Tailwind CSS 4 CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        <!--[if lt IE 9]>
                                <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                                <![endif]-->
    @show
</head>

<body class="bg-base-100 text-base-content font-sans antialiased">
    <div class="drawer lg:drawer-open min-h-screen bg-base-100">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100/80 backdrop-blur-md sticky top-0 z-30 border-b border-base-300 w-full px-4">
                <div class="flex-none lg:hidden">
                    <label for="admin-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-6 w-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <a href="{{ route('index') }}" class="flex items-center gap-2 group">
                        <div
                            class="bg-primary text-primary-content p-1.5 rounded-lg shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-black tracking-tighter uppercase">Bluedactyl</span>
                    </a>
                </div>
                <div class="flex-none gap-2">
                    <!-- Theme Controller -->
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="inline-block h-5 w-5 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content bg-base-300 rounded-box z-[1] mt-3 w-52 p-2 shadow-2xl border border-base-content/10 max-h-96 overflow-y-auto">
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Light" value="light" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Dark" value="dark" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Cupcake" value="cupcake" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Bumblebee" value="bumblebee" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Corporate" value="corporate" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Synthwave" value="synthwave" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Retro" value="retro" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Cyberpunk" value="cyberpunk" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Valentine" value="valentine" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Halloween" value="halloween" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Garden" value="garden" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Forest" value="forest" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Aqua" value="aqua" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Lofi" value="lofi" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Pastel" value="pastel" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Fantasy" value="fantasy" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Wireframe" value="wireframe" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Black" value="black" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Luxury" value="luxury" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Dracula" value="dracula" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Cmyk" value="cmyk" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Autumn" value="autumn" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Business" value="business" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Acid" value="acid" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Lemonade" value="lemonade" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Night" value="night" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Coffee" value="coffee" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Winter" value="winter" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Dim" value="dim" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Nord" value="nord" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Sunset" value="sunset" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Abyss" value="abyss" /></li>
                            <li><input type="radio" name="theme-dropdown"
                                    class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                                    aria-label="Silk" value="silk" /></li>
                        </ul>
                    </div>

                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button"
                            class="btn btn-ghost btn-circle avatar border border-base-300">
                            <div class="w-10 rounded-full">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(Auth::user()->email)) }}?s=160"
                                    alt="User Avatar" />
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow-2xl border border-base-300">
                            <li
                                class="menu-title px-4 py-2 opacity-50 font-black uppercase text-[10px] tracking-widest">
                                {{ Auth::user()->name_first }} {{ Auth::user()->name_last }}
                            </li>
                            <li><a href="{{ route('account') }}"><i class="fa fa-user"></i> Account Settings</a></li>
                            <li><a href="{{ route('index') }}"><i class="fa fa-server"></i> Exit Admin</a></li>
                            <div class="divider my-1"></div>
                            <li><a href="{{ route('auth.logout') }}" id="logoutButton"
                                    class="text-error font-bold"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4 md:p-8 grow">
                <section class="mb-8">
                    @yield('content-header')
                </section>

                <section>
                    <div class="flex flex-col gap-4 mb-8">
                        @if (count($errors) > 0)
                            <div role="alert" class="alert alert-error alert-soft border-l-4 border-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="font-black uppercase tracking-tight text-sm">Validation Error</h3>
                                    <ul class="list-disc list-inside text-xs mt-1 opacity-80">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @foreach (Alert::getMessages() as $type => $messages)
                            @foreach ($messages as $message)
                                @php
                                    $alertClass = match ($type) {
                                        'danger' => 'alert-error',
                                        'success' => 'alert-success',
                                        'warning' => 'alert-warning',
                                        'info' => 'alert-info',
                                        default => 'alert-info',
                                    };
                                @endphp
                                <div role="alert" class="alert {{ $alertClass }} alert-soft border-l-4">
                                    <span class="text-sm font-bold">{{ $message }}</span>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    @yield('content')
                </section>
            </main>

            <!-- Footer -->
            <footer class="footer footer-center p-10 bg-base-200/50 text-base-content border-t border-base-300">
                <aside>
                    <p class="font-black text-2xl tracking-tighter uppercase">
                        BLUEDACTYL
                    </p>
                    <p class="text-sm opacity-60">Copyright &copy; 2015 - {{ date('Y') }} <a
                            href="https://brickerium.com" class="link link-primary font-bold">Brickerium</a>. All
                        rights reserved.</p>
                    <div
                        class="flex items-center gap-4 mt-6 opacity-40 text-[10px] font-black uppercase tracking-widest">
                        <span><i class="fa fa-fw {{ $appIsGit ? 'fa-git-square' : 'fa-code-fork' }}"></i>
                            {{ $appVersion }}</span>
                        <span><i class="fa fa-fw fa-clock-o"></i>
                            {{ round(microtime(true) - LARAVEL_START, 3) }}s</span>
                    </div>
                </aside>
            </footer>
        </div>

        <!-- Sidebar -->
        <div class="drawer-side z-40">
            <label for="admin-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu p-4 w-80 min-h-full bg-base-100 text-base-content border-r border-base-300 shadow-2xl">
                <div class="px-4 py-8 mb-6 flex items-center gap-3">
                    <div class="bg-primary text-primary-content p-2 rounded-xl shadow-xl shadow-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black tracking-tighter block leading-none">BLUEDACTYL</span>
                        <span class="text-[10px] opacity-40 font-black tracking-widest uppercase">Admin Panel</span>
                    </div>
                </div>

                <li class="menu-title opacity-40 text-[10px] font-black tracking-widest uppercase mb-2 px-4">Core</li>
                <li>
                    <a href="{{ route('admin.index') }}"
                        class="flex gap-4 py-3 rounded-xl {{ Route::currentRouteName() !== 'admin.index' ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-grid-1x2-fill text-lg"></i> Overview
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.settings') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-sliders text-lg"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.api.index') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.api') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-key-fill text-lg"></i> Application API
                    </a>
                </li>

                <li class="menu-title opacity-40 text-[10px] font-black tracking-widest uppercase mt-8 mb-2 px-4">
                    Management</li>
                <li>
                    <a href="{{ route('admin.databases') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.databases') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-database-fill text-lg"></i> Databases
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.locations') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.locations') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-geo-alt-fill text-lg"></i> Locations
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.nodes') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.nodes') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-server text-lg"></i> Nodes
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.servers') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.servers') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-cpu-fill text-lg"></i> Servers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.users') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-people-fill text-lg"></i> Users
                    </a>
                </li>

                <li class="menu-title opacity-40 text-[10px] font-black tracking-widest uppercase mt-8 mb-2 px-4">
                    Services</li>
                <li>
                    <a href="{{ route('admin.mounts') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.mounts') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-folder-fill text-lg"></i> Mounts
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.nests') }}"
                        class="flex gap-4 py-3 rounded-xl {{ !starts_with(Route::currentRouteName(), 'admin.nests') ?: 'menu-active font-bold shadow-lg shadow-primary/10' }}">
                        <i class="bi bi-box-seam-fill text-lg"></i> Nests
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @section('footer-scripts')
        <script src="/js/keyboard.polyfill.js" type="application/javascript"></script>
        <script>
            keyboardeventKeyPolyfill.polyfill();

            // Theme persistence
            const theme = localStorage.getItem('admin-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);

            document.querySelectorAll('.theme-controller').forEach(input => {
                if (input.value === theme) {
                    input.checked = true;
                }
                input.addEventListener('change', (e) => {
                    const newTheme = e.target.value;
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('admin-theme', newTheme);
                });
            });
        </script>

        {!! Theme::js('vendor/jquery/jquery.min.js?t={cache-version}') !!}
        {!! Theme::js('vendor/sweetalert/sweetalert.min.js?t={cache-version}') !!}
        {!! Theme::js('vendor/slimscroll/jquery.slimscroll.min.js?t={cache-version}') !!}
        {!! Theme::js('vendor/select2/select2.full.min.js?t={cache-version}') !!}
        {!! Theme::js('js/admin/functions.js?t={cache-version}') !!}
        <script src="/js/autocomplete.js" type="application/javascript"></script>

        @if (Auth::user()->root_admin)
            <script>
                $('#logoutButton').on('click', function(event) {
                    event.preventDefault();

                    var that = this;
                    swal({
                        title: 'Do you want to log out?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d9534f',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Log out'
                    }, function() {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('auth.logout') }}',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            complete: function() {
                                window.location.href = '{{ route('auth.login') }}';
                            }
                        });
                    });
                });
            </script>
        @endif
    @show
</body>

</html>

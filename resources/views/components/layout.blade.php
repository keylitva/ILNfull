<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILN</title>
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="ILN" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<style>
    #mobile-menu {
        max-height: 0;
        opacity: 0;
        overflow: visible;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.15s ease-out;
    }

    #mobile-menu.active {
        max-height: 500px;
        /* Adjust based on your content height */
        opacity: 1;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease-in;
    }
</style>

<body class="h-screen bg-[#080A25]">
    <div class="flex flex-col min-h-screen">
        <nav class="bg-[#080A25] pt-4 pb-3 shadow-3xl">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <a href="/">
                                <img class="h-32 w-32" src="{{ asset('images/' . 'iln_transparent.svg') }}" alt="ILN">
                            </a>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                                <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
                                <x-nav-link href="/help" :active="request()->is('help')">Help</x-nav-link>
                                <x-nav-link href="/test" :active="request()->is('test*')">Test</x-nav-link>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Dropdown button on the same level as the logo -->
                    <div class="md:hidden">
                        <button id='mobile-menu-toggle'
                            class="group inline-flex w-12 h-12 text-white bg-[#080A25] text-center items-center justify-center rounded shadow-[0_1px_0_theme(colors.slate.950/.04),0_1px_2px_theme(colors.slate.950/.12),inset_0_-2px_0_theme(colors.slate.950/.04)] hover:shadow-[0_1px_0_theme(colors.slate.950/.04),0_4px_8px_theme(colors.slate.950/.12),inset_0_-2px_0_theme(colors.slate.950/.04)] transition"
                            aria-pressed="false" onclick="toggleMenu()">
                            <span class="sr-only">Menu</span>
                            <svg class="w-6 h-6 fill-current pointer-events-none" viewBox="0 0 16 16"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect
                                    class="origin-center -translate-y-[5px] translate-x-[7px] transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.1)] group-[[aria-pressed=true]]:translate-x-0 group-[[aria-pressed=true]]:translate-y-0 group-[[aria-pressed=true]]:rotate-[315deg]"
                                    y="7" width="9" height="2" rx="1"></rect>
                                <rect
                                    class="origin-center transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.8)] group-[[aria-pressed=true]]:rotate-45"
                                    y="7" width="16" height="2" rx="1"></rect>
                                <rect
                                    class="origin-center translate-y-[5px] transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.1)] group-[[aria-pressed=true]]:translate-y-0 group-[[aria-pressed=true]]:-rotate-[225deg]"
                                    y="7" width="9" height="2" rx="1"></rect>
                            </svg>
                        </button>
                    </div>

                    @auth
                        <div class="hidden md:block">
                            <div class="ml-4 flex items-center md:ml-6">
                                <x-notification-dropdown />
                                <x-dropdown />
                            </div>
                        </div>
                    @endauth

                    @guest
                    <div class="hidden md:block">
                        <div class="pt-4 pb-3 flex justify-between items-center">
                            <a href="{{ route('login') }}"
                                class="px-8 py-3 bg-white/10 backdrop-blur-xl border border-white/20 text-white rounded-2xl hover:bg-white/20 transition-all text-center shadow-lg">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="ml-4 px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white border border-white/20 rounded-2xl hover:opacity-90 transition-all text-center shadow-lg">
                                Register
                            </a>
                        </div>
                    </div>
                @endguest
                </div>

                <!-- Mobile menu -->
                <div class="md:hidden">
                    <div id="mobile-menu" class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                        <!-- Navigation Links -->
                        <x-nav-link-mobile href="/" :active="request()->is('/')">Home</x-nav-link-mobile>
                        <x-nav-link-mobile href="/about" :active="request()->is('about')">About</x-nav-link-mobile>
                        <x-nav-link-mobile href="/help" :active="request()->is('help')">Help</x-nav-link-mobile>
                        <x-nav-link-mobile href="/test" :active="request()->is('test*')">Test</x-nav-link-mobile>

                        <!-- Profile and Notifications -->
                        @auth
                            <div class="border-t border-gray-700 pt-4 pb-3 flex justify-between items-center">
                                <x-user-profile />
                                <x-notification-icon />
                            </div>
                        @endauth

                        @guest
                            <div class="border-t border-gray-700 pt-4 pb-3 flex justify-between items-center">
                                <a href="{{ route('login') }}"
                                    class="px-8 py-3 bg-white/10 backdrop-blur-xl border border-white/20 text-white rounded-2xl hover:bg-white/20 transition-all text-center shadow-lg">
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                    class="ml-4 px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white border border-white/20 rounded-2xl hover:opacity-90 transition-all text-center shadow-lg">
                                    Register
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
        <script>
            function toggleMenu() {
                const button = document.getElementById('mobile-menu-toggle');
                const menu = document.getElementById('mobile-menu');
                const isPressed = button.getAttribute('aria-pressed') === 'true';

                button.setAttribute('aria-pressed', !isPressed);
                menu.classList.toggle('active');

                // Prevent body scroll when menu is open
                document.body.style.overflow = isPressed ? 'auto' : 'hidden';
            }

            // Close menu when clicking outside (but ignore dropdowns)
            document.addEventListener('click', function(event) {
                const button = document.getElementById('mobile-menu-toggle');
                const menu = document.getElementById('mobile-menu');
                const dropdown = document.querySelector('[x-data]'); // Catch Alpine.js dropdown
                const isMenuOpen = button.getAttribute('aria-pressed') === 'true';

                if (
                    !button.contains(event.target) &&
                    !menu.contains(event.target) &&
                    !dropdown.contains(event.target) && // Prevent dropdown from closing the menu
                    isMenuOpen
                ) {
                    toggleMenu();
                }
            });
        </script>
        <main {{ $attributes }} class="flex-grow">

            @if (session('success'))
                <div id="success-message"
                    class="alert alert-success p-4 mb-4 bg-green-500 text-white rounded-lg shadow-md transform transition-all duration-500 opacity-100 animate-slideInUp w-1/3 absolute left-1/2 top-6 -translate-x-1/2">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="error-message"
                    class="alert alert-error p-4 mb-4 bg-red-500 text-white rounded-lg shadow-md transform transition-all duration-500 opacity-100 animate-slideInUp w-1/3 absolute left-1/2 top-6 -translate-x-1/2">
                    {{ session('error') }}
                </div>
            @endif

            <script>
                // Wait for the DOM to load before running the script
                document.addEventListener('DOMContentLoaded', function() {
                    const successMessage = document.getElementById('success-message');
                    const errorMessage = document.getElementById('error-message');

                    // Set the fade-out effect after 5 seconds
                    if (successMessage) {
                        setTimeout(() => {
                            successMessage.classList.add('opacity-0');
                            successMessage.classList.remove('opacity-100');
                        }, 3000);
                    }

                    if (errorMessage) {
                        setTimeout(() => {
                            errorMessage.classList.add('opacity-0');
                            errorMessage.classList.remove('opacity-100');
                        }, 3000);
                    }
                });
            </script>

            <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <footer class="bg-[#080A25]">
            <div class="mx-auto w-full max-w-screen-xl px-4 py-8 lg:py-12">
                <div class="md:flex md:justify-between">
                    <!-- Logo Section -->
                    <div class="mb-8 md:mb-0 flex justify-center md:justify-start">
                        <a href="/" class="group flex items-center gap-4 hover:-translate-y-1 transition-transform">
                            <img src="{{ asset('images/iln_transparent.svg') }}" 
                                 class="h-20 md:h-24 w-auto opacity-90 group-hover:opacity-100 transition-opacity"
                                 alt="ILN logo" />
                            <span class="text-xl md:text-2xl font-semibold text-white bg-gradient-to-r from-blue-200 to-purple-200 bg-clip-text text-transparent">
                                Išmok, Laikyk, Nugalėk
                            </span>
                        </a>
                    </div>
        
                    <!-- Links Grid -->
                    <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 sm:gap-12">
                        <!-- Resources -->
                        <div class="text-center sm:text-left">
                            <h2 class="mb-4 text-sm font-semibold uppercase text-white/90 tracking-wider">Resources</h2>
                            <ul class="space-y-3 text-gray-300 font-medium">
                                <li><a href="/" class="hover:text-white transition-colors">Main</a></li>
                                <li><a href="/test" class="hover:text-white transition-colors">Test</a></li>
                            </ul>
                        </div>
        
                        <!-- Follow Us -->
                        <div class="text-center sm:text-left">
                            <h2 class="mb-4 text-sm font-semibold uppercase text-white/90 tracking-wider">Follow Us</h2>
                            <ul class="space-y-3 text-gray-300 font-medium">
                                <li><a href="#" class="hover:text-white transition-colors">Instagram</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">X</a></li>
                            </ul>
                        </div>
        
                        <!-- Legal -->
                        <div class="text-center sm:text-left">
                            <h2 class="mb-4 text-sm font-semibold uppercase text-white/90 tracking-wider">Legal</h2>
                            <ul class="space-y-3 text-gray-300 font-medium">
                                <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">Terms</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        
                <!-- Divider -->
                <div class="my-6 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent lg:my-8"></div>
        
                <!-- Bottom Section -->
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
                    <span class="text-sm text-gray-300 text-center">
                        © 2025 <a href="#" class="hover:text-white transition-colors">ILN™</a>. All Rights Reserved.
                    </span>
                    
                    <!-- Social Icons -->
                    <div class="flex justify-center gap-6">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 8 19">
                                <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z"/>
                            </svg>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                            <span class="sr-only">Instagram</span>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 17">
                                <path fill-rule="evenodd" d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z"/>
                            </svg>
                            <span class="sr-only">Twitter</span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILN</title>
    <script src="https://cdn.tailwindcss.com/"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-screen bg-[#080A25]">
<div class="flex flex-col min-h-screen">
  <nav class="bg-[#080A25] pt-4 pb-3 shadow-3xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0">
            <img class="h-8 w-16" src="https://keylitva.com/public/img/logo-white.png" alt="ILN">
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <x-nav-link href="/tests/dashboard/" :active="request()->is('/')">Home</x-nav-link>
              <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
              <x-nav-link href="/help" :active="request()->is('help')">Help</x-nav-link>
              <x-nav-link href="/test" :active="request()->is('test*')">Test</x-nav-link>
            </div>
          </div>
        </div>
  
        <!-- Mobile Dropdown button on the same level as the logo -->
        <div class="md:hidden">
          <button id='mobile-menu-toggle' class="group inline-flex w-12 h-12 text-white bg-[#080A25] text-center items-center justify-center rounded shadow-[0_1px_0_theme(colors.slate.950/.04),0_1px_2px_theme(colors.slate.950/.12),inset_0_-2px_0_theme(colors.slate.950/.04)] hover:shadow-[0_1px_0_theme(colors.slate.950/.04),0_4px_8px_theme(colors.slate.950/.12),inset_0_-2px_0_theme(colors.slate.950/.04)] transition" aria-pressed="false" onclick="this.setAttribute('aria-pressed', !(this.getAttribute('aria-pressed') === 'true'))">
              <span class="sr-only">Menu</span>
              <svg class="w-6 h-6 fill-current pointer-events-none" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                  <rect class="origin-center -translate-y-[5px] translate-x-[7px] transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.1)] group-[[aria-pressed=true]]:translate-x-0 group-[[aria-pressed=true]]:translate-y-0 group-[[aria-pressed=true]]:rotate-[315deg]" y="7" width="9" height="2" rx="1"></rect>
                  <rect class="origin-center transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.8)] group-[[aria-pressed=true]]:rotate-45" y="7" width="16" height="2" rx="1"></rect>
                  <rect class="origin-center translate-y-[5px] transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.1)] group-[[aria-pressed=true]]:translate-y-0 group-[[aria-pressed=true]]:-rotate-[225deg]" y="7" width="9" height="2" rx="1"></rect>
              </svg>
          </button>
      </div>
        
      @auth
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <x-notification-dropdown />
                    <x-dropdown :items="[
                        ['label' => 'Your Profile', 'url' => route('profile')],
                        ['label' => 'Settings', 'url' => route('settings')],
                        ['label' => 'Sign out', 'url' => route('logout')],
                    ]" />
                </div>
            </div>
        @endauth
        
        @guest
            <div class="hidden md:block">
              <div class="pt-4 pb-3 flex justify-between items-center">
                  <a href="{{ route('login') }}" class="px-4 py-2 border border-white text-white rounded-md hover:bg-gray-800 transition text-center">
                      Login
                  </a>
              </div>
            </div>
        @endguest
      </div>
  
      <!-- Mobile menu, show/hide based on menu state -->
      <div class="md:hidden">
        <!-- Mobile Dropdown menu -->
        <div id="mobile-menu" class="space-y-1 px-2 pt-2 pb-3 sm:px-3 hidden">
          <!-- Navigation Links -->
          <x-nav-link-mobile href="/" :active="request()->is('/')">Home</x-nav-link-mobile>
          <x-nav-link-mobile href="/about" :active="request()->is('about')">About</x-nav-link-mobile>
          <x-nav-link-mobile href="/help" :active="request()->is('help')">Help</x-nav-link-mobile>
          <x-nav-link-mobile href="/test" :active="request()->is('test*')">Test</x-nav-link-mobile>
  
          <!-- Profile and Notifications in the dropdown -->
          @auth
              <div class="border-t border-gray-700 pt-4 pb-3 flex justify-between items-center">
                  <!-- Profile on the far left -->
                  <x-user-profile 
                      :profileImage="auth()->user()->profile_image ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'" 
                      :userName="auth()->user()->name" 
                      :userEmail="auth()->user()->email" 
                  />

                  <!-- Notifications on the far right -->
                  <x-notification-icon />
              </div>
          @endauth

          @guest
              <div class="border-t border-gray-700 pt-4 pb-3 flex justify-between items-center">
                  <a href="{{ route('login') }}" class="px-4 py-2 border border-white text-white rounded-md hover:bg-gray-800 transition w-full text-center">
                      Login
                  </a>
              </div>
          @endguest
        </div>
      </div>
    </div>
  </nav>
  
  <!-- Add this script to toggle the mobile menu visibility and animate the hamburger -->
  <script>
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
  
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>
  <main {{ $attributes }} class="flex-grow">
    <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">
      {{ $slot }}
    </div>
  </main>

  <footer class="bg-[#080A25]">
    <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
        <div class="md:flex md:justify-between">
          <div class="mb-6 md:mb-0">
              <a href="https://keylitva.com/" class="flex items-center">
                  <img src="https://keylitva.com/public/img/logo-white.png" class="h-8 me-3" alt="FlowBite Logo" />
                  <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Išmok, Laikyk, Nugalėk</span>
              </a>
          </div>
          <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
              <div>
                  <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
                  <ul class="text-gray-500 dark:text-gray-400 font-medium">
                      <li class="mb-4">
                          <a href="/" class="hover:underline">Main</a>
                      </li>
                      <li>
                          <a href="/test" class="hover:underline">Test</a>
                      </li>
                  </ul>
              </div>
              <div>
                  <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us</h2>
                  <ul class="text-gray-500 dark:text-gray-400 font-medium">
                      <li class="mb-4">
                          <a href="#" class="hover:underline ">Intagram</a>
                      </li>
                      <li>
                          <a href="#" class="hover:underline">X</a>
                      </li>
                  </ul>
              </div>
              <div>
                  <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                  <ul class="text-gray-500 dark:text-gray-400 font-medium">
                      <li class="mb-4">
                          <a href="#" class="hover:underline">Privacy Policy</a>
                      </li>
                      <li>
                          <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
      <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
      <div class="sm:flex sm:items-center sm:justify-between">
          <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="" class="hover:underline">ILN™</a>. All Rights Reserved.
          </span>
          <div class="flex mt-4 sm:justify-center sm:mt-0">
              <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                  <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                        <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd"/>
                    </svg>
                  <span class="sr-only">Facebook page</span>
              </a>
              <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" clip-rule="evenodd"/>
                </svg>
                <span class="sr-only">Instagram profile</span>
              </a>
              <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                  <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 17">
                    <path fill-rule="evenodd" d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z" clip-rule="evenodd"/>
                </svg>
                  <span class="sr-only">Twitter page</span>
              </a>
          </div>
      </div>
    </div>
</footer>
</div>
</body>
</html>
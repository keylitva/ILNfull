<div class="pt-4 pb-3 flex justify-between items-center">
    <!-- Profile on the far left with Dropdown -->
    <div class="relative ml-3" x-data="{ open: false }">
        <!-- Profile Image (Dropdown Trigger) -->
        <button 
            @click="open = !open" 
            @click.outside="open = false"
            class="rounded-full w-8 h-8 cursor-pointer focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
            id="user-menu-button" 
            aria-expanded="false" 
            aria-haspopup="true"
        >
            <img 
            class="rounded-full w-8 h-8"
            src="{{ url('/profile-picture/' . Auth::user()->name) }}" 
            alt="Profile"
            />
        </button>

        <!-- Dropdown Menu -->
        <div 
            x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-100 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-gray-800 py-1 shadow-lg ring-1 ring-black/5 focus:outline-none"
            role="menu" 
            aria-orientation="vertical"
        >
            <div class="block px-4 py-2 text-sm text-white">
                Welcome, {{ Auth::user()->name }}
            </div>
            <hr class="border-t border-gray-700 my-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-600" role="menuitem">Dashboard</a>
            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-600" role="menuitem">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-white hover:bg-gray-600" role="menuitem">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</div>
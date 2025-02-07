<div class="pt-4 pb-3 flex justify-between items-center">
    <!-- Profile on the far left with Dropdown -->
    <div class="relative ml-3" x-data="{ open: false }">
        <!-- Profile Image (Dropdown Trigger) -->
        <img 
            @click="open = !open" 
            @click.outside="open = false"
            class="w-8 h-8 rounded-full cursor-pointer"
            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
            alt="Profile"
            id="user-menu-button" 
            aria-expanded="false" 
            aria-haspopup="true"
        />

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
            class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none"
            role="menu" 
            aria-orientation="vertical"
        >
            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</div>
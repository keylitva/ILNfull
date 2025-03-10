<div class="relative ml-auto shrink-0" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        @click.outside="open = false"
        class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
        aria-haspopup="true"
        aria-expanded="open"
    >
        <span class="sr-only">View notifications</span>
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
    </button>

    <!-- Dropdown -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-800 py-1 shadow-lg ring-1 ring-black/5 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
    >
        <div class="px-4 py-2 text-sm text-white">
            <p class="font-medium">Notifications</p>
        </div>
        <a href="#" class="block px-4 py-2 text-sm text-white hover:bg-gray-600">View all notifications</a>
    </div>
</div>
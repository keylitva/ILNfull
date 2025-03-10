<x-layout class="bg-[#080A25] min-h-screen">
    <div class="container mx-auto px-4 py-8" x-data="testFilter({{ $tests->toJson() }})">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-8 backdrop-blur-sm bg-gray-900/50 p-4 md:p-6 rounded-2xl border border-gray-700/30 space-y-4 md:space-y-0">
            <h1
                class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-600 text-center md:text-left">
                My Tests
            </h1>

            <div class="flex justify-between items-center w-full md:w-auto space-x-4 md:space-x-6">
                <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    <a href="/tests/create"
                        class="flex items-center justify-center px-4 py-2.5 md:px-6 md:py-3 text-sm md:text-base rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow-md hover:shadow-xl transition-all duration-300 active:scale-95">
                        Create Test
                    </a>
                </div>

                <!-- Toggle Button -->
                <button @click="$store.slidingMenu.toggle()"
                    class="p-3 md:p-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <!-- Sliding Menu -->
            <div x-show="$store.slidingMenu.open" @click.away="$store.slidingMenu.open = false"
                x-transition:enter="transition-transform duration-500 ease-in-out"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition-transform duration-500 ease-in-out"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="fixed top-0 right-0 w-80 h-full bg-gray-900/90 backdrop-blur-lg shadow-lg p-6 space-y-6 rounded-l-2xl z-50">
                <!-- Search Input with Icon -->
                <div class="relative w-full md:w-auto">
                    <input type="text" placeholder="Search tests..."
                        class="w-full px-5 py-3 pl-12 rounded-xl bg-white/10 backdrop-blur-sm text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
                        x-model="searchQuery">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/60"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2a7.5 7.5 0 010 14.65z" />
                    </svg>
                </div>

                <!-- Select Dropdown -->
                <div class="relative w-full md:w-auto">
                    <select
                        class="w-full px-5 py-3 pr-10 rounded-xl bg-white/10 backdrop-blur-sm text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none transition-all duration-300"
                        x-model="statusFilter" x-init="statusFilter = 'all'">
                        <option value="all" class="bg-[#080A25]">All Statuses</option>
                        <option value="ready" class="bg-[#080A25]">Active</option>
                        <option value="not-ready" class="bg-[#080A25]">Inactive</option>
                    </select>

                    <!-- Dropdown Arrow -->
                    <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/70 pointer-events-none"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <!-- Mass Action Buttons -->
                <div class="space-y-4">
                    <form id="mass-activate-form" action="/tests/mass-activate" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="selected_tests" x-bind:value="selectedTests.join(',')">
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to activate the selected tests? This action cannot be undone.')"
                            class="w-full px-6 py-3 rounded-xl bg-green-600 text-white font-semibold shadow-md hover:bg-green-500 transition-all duration-300"
                            :disabled="selectedTests.length === 0">
                            Mass Activate
                        </button>
                    </form>

                    <form id="mass-deactivate-form" action="/tests/mass-deactivate" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="selected_tests" x-bind:value="selectedTests.join(',')">
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to deactivate the selected tests? This action cannot be undone.')"
                            class="w-full px-6 py-3 rounded-xl bg-yellow-600 text-white font-semibold shadow-md hover:bg-yellow-500 transition-all duration-300"
                            :disabled="selectedTests.length === 0">
                            Mass Deactivate
                        </button>
                    </form>

                    <form id="mass-delete-form" action="/tests/mass-delete" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="selected_tests" x-bind:value="selectedTests.join(',')">
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to permanently delete the selected tests? This action cannot be undone.')"
                            class="w-full px-6 py-3 rounded-xl bg-red-600 text-white font-semibold shadow-md hover:bg-red-500 transition-all duration-300"
                            :disabled="selectedTests.length === 0">
                            Mass Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <template x-for="test in filteredTests()" :key="test.id || test.test_alternative_id">
                <div
                    class="relative bg-gradient-to-br from-[#080A25] to-[#1A2038] rounded-2xl border border-white/20 p-5 transition-all transform hover:scale-105 hover:border-white/30 flex flex-col justify-between">

                    <!-- Header Section: Test Info -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                        <div class="flex items-center gap-4">
                            <h2 class="text-xl font-semibold text-white truncate" x-text="test.test_name"></h2>
                            <span class="px-3 py-1 text-xs rounded-full font-medium"
                                :class="test.is_active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400'"
                                x-text="test.is_active ? 'Active' : 'Inactive'"></span>
                        </div>
                        <label :for="'checkbox-' + test.id" class="flex items-center cursor-pointer">
                            <input type="checkbox" :id="'checkbox-' + test.id" :value="test.id"
                                x-model="selectedTests" class="sr-only peer">
                            <span
                                class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-all after:w-4 after:h-4 after:top-[2px] after:left-[2px] peer-checked:after:translate-x-4"></span>
                        </label>
                    </div>

                    <!-- Test Metrics -->
                    <div class="bg-white/5 p-4 rounded-lg text-sm space-y-3 mb-4">
                        <p class="flex justify-between">
                            <span class="text-white/60">Attempts:</span>
                            <span class="font-medium text-white" x-text="test.attempts_count"></span>
                        </p>
                        <p class="flex justify-between">
                            <span class="text-white/60">Avg Score:</span>
                            <span class="font-medium text-white"
                                x-text="test.average_score ? test.average_score + '%' : 'N/A'"></span>
                        </p>
                        <p class="flex justify-between">
                            <span class="text-white/60">Last Attempt:</span>
                            <span class="font-medium text-white"
                                x-text="test.last_attempt ? timeAgo(test.last_attempt) : 'Never'"></span>
                        </p>
                    </div>

                    <!-- Fixed Description Box with Fade Effect -->
                    <div class="bg-white/5 p-4 rounded-lg text-sm mb-4 relative h-24 overflow-hidden">
                        <p class="text-white/80 line-clamp-3" x-text="test.description"></p>
                        <div class="absolute bottom-0 left-0 right-0 h-4 pointer-events-none"
                            style="background: linear-gradient(to top, rgba(26,32,56,0.8), rgba(26,32,56,0));">
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="flex flex-col sm:flex-row sm:justify-between mb-4">
                        <span class="px-3 py-1 rounded-md bg-white/5 text-white text-sm">
                            Code: <span x-text="test.test_alternative_id"></span>
                        </span>
                        <span class="px-3 py-1 rounded-md bg-white/5 text-white text-sm">
                            Time: <span x-text="test.time_limit_minutes"></span>m
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <a :href="`/tests/results/${test.id}`"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg transition-all hover:from-indigo-600 hover:to-purple-600">
                            View Results
                        </a>
                        <a :href="`/tests/edit/${test.id}`"
                            class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg transition-all hover:bg-gray-600">
                            Edit
                        </a>
                        <form :action="`/tests/delete/${test.id}`" method="POST" class="inline-block">
                            @csrf
                            @method('POST')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg transition-all hover:bg-red-500"
                                onclick="return confirm('Are you sure you want to delete this test?')">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </template>
        </div>

        <!-- No Tests Found Message -->
        <div x-show="filteredTests().length === 0" class="py-8 text-center">
            <p class="text-lg text-white/60 md:text-xl">No tests found. Create your first test! 🚀</p>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                // Create a global store for just this sliding menu
                Alpine.store('slidingMenu', {
                    open: false,
                    toggle() {
                        this.open = !this.open;
                    }
                });
            });

            // Function to show confirmation dialog for both actions (Activate and Deactivate)
            function confirmAction(action) {
                const actionText = action === 'activate' ? 'activate' : 'deactivate';
                const confirmation = confirm(
                    `Are you sure you want to ${actionText} the selected tests? This action cannot be undone.`);
                return confirmation; // If 'OK' is clicked, the form will submit; otherwise, it will not submit.
            }

            function confirmDelete() {
                const confirmation = confirm("Are you sure you want to delete these tests? This action cannot be undone.");
                return confirmation; // If 'OK' is clicked, the form will submit, else it will not submit.
            }

            document.addEventListener('alpine:init', () => {
                Alpine.data('testFilter', (initialTests) => ({
                    searchQuery: '',
                    statusFilter: '',
                    selectedTests: [], // Store selected test IDs
                    tests: initialTests,

                    filteredTests() {
                        return this.tests.filter(test => {
                            const matchesSearch = test.test_name.toLowerCase().includes(this
                                    .searchQuery.toLowerCase()) ||
                                test.description.toLowerCase().includes(this.searchQuery
                                    .toLowerCase());

                            const matchesStatus = this.statusFilter === 'all' ||
                                (this.statusFilter === 'ready' && test.is_active) ||
                                (this.statusFilter === 'not-ready' && !test.is_active);

                            return matchesSearch && matchesStatus;
                        });
                    },

                    timeAgo(dateString) {
                        const date = new Date(dateString);
                        const now = new Date();
                        const seconds = Math.floor((now - date) / 1000);

                        if (seconds < 60) return `${seconds} seconds ago`;
                        const minutes = Math.floor(seconds / 60);
                        if (minutes < 60) return `${minutes} minutes ago`;
                        const hours = Math.floor(minutes / 60);
                        if (hours < 24) return `${hours} hours ago`;
                        const days = Math.floor(hours / 24);
                        if (days < 30) return `${days} days ago`;
                        const months = Math.floor(days / 30);
                        if (months < 12) return `${months} months ago`;
                        const years = Math.floor(months / 12);
                        return `${years} years ago`;
                    }
                }));
            });
        </script>
</x-layout>

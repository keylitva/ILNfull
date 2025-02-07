@php
use App\Models\TestAttempt;
@endphp

<x-layout class="bg-[#080A25] min-h-screen">
    <div class="container mx-auto px-4 py-8" x-data="testFilter({{ $tests->toJson() }})">
        <!-- Header and Filter Section -->
        <div class="flex flex-wrap md:flex-nowrap justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white w-full md:w-auto text-center md:text-left">My Tests</h1>
            
            <div class="flex flex-wrap md:flex-nowrap gap-4 w-full md:w-auto">
                <!-- Search Input -->
                <input 
                    type="text" 
                    placeholder="Search tests..." 
                    class="w-full md:w-64 px-4 py-2 rounded-md bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    x-model="searchQuery"
                >
                
                <!-- Select Dropdown -->
                <div class="relative w-full md:w-64">
                    <select 
                        class="w-full px-4 py-2 pr-10 rounded-md bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
                        x-model="statusFilter"
                        x-init="statusFilter = 'all'"
                    >
                        <option value="all" class="bg-[#080A25] text-white">All Statuses</option>
                        <option value="ready" class="bg-[#080A25] text-white">Active</option>
                        <option value="not-ready" class="bg-[#080A25] text-white">Inactive</option>
                    </select>
        
                    <!-- Dropdown Arrow -->
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" 
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tests Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="test in filteredTests()" :key="test.id || test.test_alternative_id">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <!-- Test Header -->
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-800" x-text="test.test_name"></h2>
                        <span class="px-3 py-1 rounded-full text-sm" 
                            :class="test.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                            x-text="test.is_active ? 'Active' : 'Inactive'">
                        </span>
                    </div>

                   <!-- Test Metrics -->
                    <div class="mb-4 space-y-2">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Attempts: <span x-text="test.attempts_count"></span></p>
                            <p class="text-sm text-gray-600">
                                Average Score: <span x-text="test.average_score ? test.average_score + '%' : 'N/A'"></span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Last Attempt: <span x-text="test.last_attempt ? timeAgo(test.last_attempt) : 'Never'"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Test Details -->
                    <div class="mb-4 space-y-2">
                        <p class="text-gray-600" x-text="test.description"></p>
                        <p class="text-sm text-gray-500">Test Code: <span x-text="test.test_alternative_id"></span></p>
                        <p class="text-sm text-gray-500">Time Limit: <span x-text="test.time_limit_minutes"></span> minutes</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-2 mt-4">
                        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                           View Results
                        </a>
                        <a href="#" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                            Edit
                        </a>
                        <form :action="`/tests/delete/${test.id}`" method="POST" class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                onclick="return confirm('Are you sure you want to delete this test?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>

        <!-- No Tests Found Message -->
        <div x-show="filteredTests().length === 0" class="col-span-full text-center py-12">
            <p class="text-gray-400 text-lg">No tests found. Create your first test!</p>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('testFilter', (initialTests) => ({
                searchQuery: '',
                statusFilter: '',
                tests: initialTests,

                filteredTests() {
    console.log(this.tests);  // Add this to check the data structure
    return this.tests.filter(test => {
        const matchesSearch = test.test_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                test.description.toLowerCase().includes(this.searchQuery.toLowerCase());

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
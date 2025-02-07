<x-layout class="h-full bg-[#080A25]"> 
    <div class="flex justify-between items-center py-6 flex-col">
        <div class="flex items-center justify-center h-screen">
            <div class="bg-white p-8 rounded-md shadow-lg w-96">
                <h1 class="flex justify-center text-3xl font-semibold text-black">Search for a Test</h1>
                @csrf
                <!-- Test Search Form -->
                <form action="/search_test" method="POST" class="space-y-4">
                    @csrf
                    <!-- Input 1: Test Name -->
                    <div>
                        <label for="test_code" class="block text-lg font-medium text-black">Test Code</label>
                        <input type="text" id="test_code" name="test_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter test code">
                    </div>
        
                    <!-- Input 2: Category -->
                    <div>
                        <label for="test-user-id" class="block text-lg font-medium text-black">Identety</label>
                        <input type="text" id="test_user" name="test_user" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter identification">
                    </div>
        
                    <!-- Search Button -->
                    <div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-lg">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
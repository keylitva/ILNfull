<x-layout class="h-full bg-[#080A25]"> 
    <div class="flex justify-between items-center py-6 flex-col">
        <div class="flex items-center justify-center h-screen">
            <div class="bg-white p-8 rounded-md shadow-lg w-96">
                <h1 class="flex justify-center text-3xl font-semibold text-black">Log in</h1>
                @csrf
                <!-- Test Search Form -->
                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <!-- Input 1: Test Name -->
                    <div>
                        <label for="email" class="block text-lg font-medium text-black">Email</label>
                        <input type="text" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter your email">
                    </div>
        
                    <!-- Input 2: Category -->
                    <div>
                        <label for="password" class="block text-lg font-medium text-black">Password</label>
                        <input type="text" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter your password">
                    </div>
        
                    <!-- Search Button -->
                    <div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-lg">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
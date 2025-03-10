<x-layout class="h-full bg-[#080A25]">
    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
            <form action="/register" method="POST" class="space-y-6">
                @csrf
                <h2 class="text-2xl font-semibold text-center text-gray-800">Create an Account</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your name" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Surname -->
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                        <input type="text" id="surname" name="surname" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your surname" required>
                        @error('surname')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your email" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Email -->
                    <div>
                        <label for="email_confirmation" class="block text-sm font-medium text-gray-700">Confirm Email</label>
                        <input type="email" id="email_confirmation" name="email_confirmation" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your email again" required>
                        @error('email_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your password" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter the password again" required>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="px-6 py-2 border border-gray-700 text-gray-700 rounded-3xl hover:bg-gray-200 transition">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
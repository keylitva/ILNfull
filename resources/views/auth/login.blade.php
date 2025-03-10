<x-layout class="h-full bg-[#080A25]">
    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
            <form action="/login" method="POST" class="space-y-6">
                @csrf
                <h2 class="text-2xl font-semibold text-center text-gray-800">Log in</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your password" required>
                </div>
                </div>
                @if ($errors->has('error'))
                    <div class="text-red-500 text-center text-sm">{{ $errors->first('error') }}</div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="px-6 py-2 border border-gray-700 text-gray-700 rounded-3xl hover:bg-gray-200 transition">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
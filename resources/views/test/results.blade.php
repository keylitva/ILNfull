<x-layout class="h-full bg-[#080A25]">
    <div class="flex justify-between items-center py-6 flex-col">
        <div class="flex items-center justify-center h-screen">
            <div class="bg-white p-8 rounded-md shadow-lg w-96">
                <h1 class="flex justify-center text-3xl font-semibold text-black">Test Results</h1>
                
                <div class="mt-4 space-y-4">
                    <div class="text-lg font-medium text-black">
                        <p><strong>Score:</strong> {{ $testAttempt->score }} %</p>
                    </div>
                    <div class="text-lg font-medium text-black">
                        <p><strong>Points Collected:</strong> {{ $testAttempt->points_collected }}</p>
                    </div>
                    <div class="text-lg font-medium text-black">
                        <p><strong>Maximum Points:</strong> {{ $testAttempt->points_max }}</p>
                    </div>
                    <div class="text-lg font-medium text-black">
                        <p><strong>Time taken:</strong> {{ \Carbon\Carbon::parse($testAttempt->time_taken)->format('H:i:s') }}</p>
                    </div>
                <!-- Back Button -->
                <div class="mt-6">
                    <a href="{{ route('test.search') }}" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-lg block text-center">
                        Return to Test Search
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
@php
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
@endphp
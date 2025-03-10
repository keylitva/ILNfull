<x-layout class="min-h-screen bg-[#080A25]">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 p-8 rounded-xl shadow-xl max-w-md w-full transition-transform duration-300 ease-in-out hover:scale-105">
            <h1 class="text-center text-3xl font-bold text-white mb-6">Test Results</h1>
            
            <div class="space-y-4">
                <div class="text-lg font-medium text-gray-200">
                    <p><span class="font-semibold">Score:</span> {{ $testAttempt->score }} %</p>
                </div>
                <div class="text-lg font-medium text-gray-200">
                    <p><span class="font-semibold">Points Collected:</span> {{ $testAttempt->points_collected }}</p>
                </div>
                <div class="text-lg font-medium text-gray-200">
                    <p><span class="font-semibold">Maximum Points:</span> {{ $testAttempt->points_max }}</p>
                </div>
                <div class="text-lg font-medium text-gray-200">
                    <p>
                        <span class="font-semibold">Time taken:</span> 
                        {{ \Carbon\Carbon::parse($testAttempt->time_taken)->format('H:i:s') }}
                    </p>
                </div>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('test.search') }}" class="block text-center w-full py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Return to Test Search
                </a>
            </div>
        </div>
    </div>
</x-layout>

@php
    session()->regenerateToken();
@endphp
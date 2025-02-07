<x-layout class=" bg-[#080A25]">
    <div class="flex justify-center items-center py-6 flex-col h-full">
        <!-- Test Data Display -->
        @if($test)
            <!-- Test Name outside the Box -->
            <header>
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <h1 class="text-[5rem] font-bold tracking-tight text-white">{{ $test->test_name }}</h1>
                </div>
            </header>
            <!-- Box containing test details -->
            <div class="bg-white p-8 rounded-md shadow-lg w-96 mt-20">
                <ul>
                    <li class="mb-6 p-4 bg-gray-100 rounded-lg">
                        <p class="text-gray-500">Time Limit: {{ $test->time_limit_minutes }} minutes</p>
                        <p class="text-gray-500">Status: {{ $test->is_active ? 'Ready' : 'Not ready' }}</p>
                        <p class="text-gray-700 text-lg">Rules & about: </p>
                        <p class="text-gray-700 text-lg">{{ $test->description }}</p>
                    </li>
                </ul>

                <!-- Start Test Button -->
                <form action="/start_test" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="test" value="{{ $test->test_id }}">
                    <div class="text-center mt-6">
                        <button type="submit" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Start Test
                        </button>
                    </div>
                </form>
            </div>
        @else
            <script>
                window.location.href = '/test';
            </script>
            <!-- Message when no test is available -->
            <div class="bg-white p-8 rounded-md shadow-lg w-96">
                <p class="text-center text-gray-500">No test available.</p>
            </div>
        @endif
    </div>
</x-layout>
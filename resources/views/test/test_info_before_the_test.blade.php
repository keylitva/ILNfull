<x-layout class="bg-[#080A25]">
    <div class="min-h-screen flex flex-col items-center justify-center p-4 relative">
        @if($test)
            <!-- Animated Header -->
            <div class="group relative max-w-4xl w-full text-center mb-12">
                <h1 class="text-6xl font-bold bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent animate-gradient-x pb-2">
                    {{ $test->test_name }}
                </h1>
                <div class="absolute inset-x-0 -bottom-4 h-1 bg-indigo-500/30 rounded-full transform group-hover:scale-x-110 transition-transform"></div>
            </div>

            <!-- Glassmorphism Details Container -->
            <div class="relative group w-full max-w-2xl">
                <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 hover:border-white/20 transition-all">
                    <div class="p-8 space-y-8">
                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Time Limit Card -->
                            <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:border-indigo-400/30 transition-colors">
                                <p class="text-sm text-indigo-300/80 mb-1">Time Limit</p>
                                <p class="text-3xl font-semibold text-indigo-100">
                                    {{ $test->time_limit_minutes }}<span class="text-lg text-indigo-300/60 ml-1">min</span>
                                </p>
                            </div>

                            <!-- Status Card -->
                            <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:border-indigo-400/30 transition-colors">
                                <p class="text-sm text-indigo-300/80 mb-1">Status</p>
                                <div class="flex items-center">
                                    <span class="flex w-3 h-3 bg-{{ $test->is_active ? 'emerald' : 'rose' }}-400 rounded-full mr-2 animate-pulse"></span>
                                    <p class="text-2xl font-semibold text-indigo-100">
                                        {{ $test->is_active ? 'Ready' : 'Locked' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="space-y-4">
                            <p class="text-lg font-semibold text-indigo-200 border-b border-white/10 pb-2">Rules & Guidelines</p>
                            <p class="text-indigo-200/80 leading-relaxed tracking-wide">
                                {{ $test->description }}
                            </p>
                        </div>

                        <!-- Start Button -->
                        <form action="/start_test" method="POST" class="pt-8">
                            @csrf
                            <input type="hidden" name="test" value="{{ $test->test_id }}">
                            <button type="submit" 
                                class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl font-bold text-lg text-white tracking-wide transform transition-all
                                       hover:scale-[1.02] hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 relative overflow-hidden"
                                @if(!$test->is_active) disabled @endif
                            >
                                <span class="relative z-10">
                                    {{ $test->is_active ? 'Begin Test' : 'Assessment Locked' }}
                                    <span class="ml-3 opacity-80">→</span>
                                </span>
                                <div class="absolute inset-0 bg-white/10 opacity-0 hover:opacity-20 transition-opacity"></div>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Background Glow -->
                <div class="absolute inset-0 -z-10 opacity-30 group-hover:opacity-50 blur-2xl transition-opacity">
                    <div class="w-full h-full bg-gradient-to-r from-indigo-500/30 to-purple-500/30 rounded-2xl"></div>
                </div>
            </div>

        @else
            <!-- No Test Available State -->
            <script>window.location.href = '/test';</script>
            <div class="relative group w-full max-w-md">
                <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-2xl p-8 text-center border border-white/10">
                    <p class="text-indigo-200/80 text-xl">No active assessments available</p>
                    <div class="mt-6">
                        <a href="/test" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                            ← Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
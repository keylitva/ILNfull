<x-layout class="h-full bg-[#080A25]">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="relative group w-full max-w-md">
            <!-- Glassmorphism Container -->
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-2xl transition-all duration-300 hover:bg-white/10 border border-white/10 hover:border-white/20">
                <div class="p-8 space-y-6">
                    <!-- Animated Header -->
                    <div class="flex flex-col items-center space-y-2">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent animate-gradient-x">
                            Test Search
                        </h1>
                        <p class="text-sm text-indigo-200/80">Enter your credentials to continue</p>
                    </div>

                    <!-- Enhanced Form -->
                    <form action="/search_test" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Test Code Input with Floating Label -->
                        <div class="relative">
                            <input 
                                type="text" 
                                id="test_code" 
                                name="test_code" 
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-indigo-50 placeholder-transparent peer focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30 transition-all"
                                placeholder=" "
                            />
                            <label 
                                for="test_code" 
                                class="absolute left-4 -top-3.5 px-1 text-indigo-300/80 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-indigo-400/60 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-indigo-300"
                            >
                                Test Code
                            </label>
                        </div>

                        <!-- Dynamic User ID Field -->
                        @auth
                            <input type="hidden" name="test_user" value="{{ Auth::user()->alternative_id }}">
                        @else
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="test_user" 
                                    name="test_user" 
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-indigo-50 placeholder-transparent peer focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30 transition-all"
                                    placeholder=" "
                                />
                                <label 
                                    for="test_user" 
                                    class="absolute left-4 -top-3.5 px-1 text-indigo-300/80 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-indigo-400/60 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-indigo-300"
                                >
                                    Unique ID
                                </label>
                            </div>
                        @endauth

                        <!-- Animated Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg font-semibold text-white tracking-wide transform transition-all 
                                   hover:scale-[1.02] hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95"
                        >
                            Search Now
                            <span class="ml-2 opacity-80">→</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Background Glow Effect -->
            <div class="absolute inset-0 -z-10 opacity-30 group-hover:opacity-50 blur-2xl transition-opacity">
                <div class="w-full h-full bg-gradient-to-r from-indigo-500/30 to-purple-500/30 rounded-2xl"></div>
            </div>
        </div>
    </div>
</x-layout>
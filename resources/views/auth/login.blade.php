<x-layout class="bg-[#080A25]">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="relative group w-full max-w-md">
            <!-- Glassmorphism Container -->
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 p-8 space-y-6">
                <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent">
                    Log in
                </h2>
                
                <form action="/login" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm text-indigo-300/80 mb-1">Email</label>
                        <input type="email" id="email" name="email" 
                            class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg shadow-sm text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                            value="{{ old('email') }}" placeholder="Enter your email" required>
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm text-indigo-300/80 mb-1">Password</label>
                        <input type="password" id="password" name="password" 
                            class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg shadow-sm text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                            placeholder="Enter your password" required>
                    </div>
                    
                    @if ($errors->has('error'))
                        <div class="text-red-400 text-center text-sm">{{ $errors->first('error') }}</div>
                    @endif
                    
                    <!-- Submit Button -->
                    <div class="flex justify-center pt-4">
                        <button type="submit" 
                            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl font-bold text-white tracking-wide transform transition-all
                                   hover:scale-[1.02] hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 relative overflow-hidden">
                            <span class="relative z-10">Log in</span>
                            <div class="absolute inset-0 bg-white/10 opacity-0 hover:opacity-20 transition-opacity"></div>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Background Glow -->
            <div class="absolute inset-0 -z-10 opacity-30 group-hover:opacity-50 blur-2xl transition-opacity">
                <div class="w-full h-full bg-gradient-to-r from-indigo-500/30 to-purple-500/30 rounded-2xl"></div>
            </div>
        </div>
    </div>
</x-layout>

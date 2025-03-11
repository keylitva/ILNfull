<x-layout class="bg-[#080A25]">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="relative group w-full max-w-lg">
            <!-- Glassmorphism Container -->
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 p-8 space-y-6">
                <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent">
                    Create an Account
                </h2>
                
                <form action="/register" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm text-indigo-300/80 mb-1">Name</label>
                            <input type="text" id="name" name="name" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                placeholder="Enter your name" required>
                        </div>
                        
                        <!-- Surname -->
                        <div>
                            <label for="surname" class="block text-sm text-indigo-300/80 mb-1">Surname</label>
                            <input type="text" id="surname" name="surname" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                placeholder="Enter your surname" required>
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm text-indigo-300/80 mb-1">Email</label>
                            <input type="email" id="email" name="email" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                placeholder="Enter your email" required>
                        </div>
                        
                        <!-- Confirm Email -->
                        <div>
                            <label for="email_confirmation" class="block text-sm text-indigo-300/80 mb-1">Confirm Email</label>
                            <input type="email" id="email_confirmation" name="email_confirmation" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                placeholder="Enter your email again" required>
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm text-indigo-300/80 mb-1">Password</label>
                            <input type="password" id="password" name="password" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                placeholder="Enter your password" required>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm text-indigo-300/80 mb-1">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-lg text-indigo-100 placeholder-indigo-300/50 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                placeholder="Enter the password again" required>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-center pt-4">
                        <button type="submit" 
                            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl font-bold text-white tracking-wide transform transition-all
                                   hover:scale-[1.02] hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 relative overflow-hidden">
                            <span class="relative z-10">Register</span>
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

<x-layout class="bg-[#080A25] min-h-screen">
    <div class="container mx-auto px-6 py-12">
      <!-- Hero sekcija -->
      <section class="flex flex-col items-center justify-center text-center mt-12">
        <h1 class="text-5xl sm:text-6xl font-extrabold text-white mb-6">
          Išmok, Laikyk, Nugalėk
        </h1>
        <p class="text-xl sm:text-2xl text-gray-300 mb-8">
          Moderni mokymosi platforma, kuri keičia švietimą
        </p>
        <a href="/about" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-full transition duration-300">
          Sužinoti daugiau
        </a>
      </section>
  
      <!-- Funkcionalumų blokai -->
      <section class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Mokiniai -->
        <div class="bg-[#1F2333] rounded-lg p-8 text-center shadow-lg hover:shadow-2xl transition-shadow duration-300">
          <div class="mb-4">
            <svg class="w-12 h-12 mx-auto text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 20l9-5-9-5-9 5 9 5z"></path>
              <path d="M12 12l9-5-9-5-9 5 9 5z"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-white mb-2">Mokiniai</h3>
          <p class="text-gray-300">
            Atrask mokomąją medžiagą, spręsk testus ir stebėk savo pažangą.
          </p>
        </div>
        <!-- Mokytojai -->
        <div class="bg-[#1F2333] rounded-lg p-8 text-center shadow-lg hover:shadow-2xl transition-shadow duration-300">
          <div class="mb-4">
            <svg class="w-12 h-12 mx-auto text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 12l2-2 4 4 8-8 2 2-10 10-6-6z"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-white mb-2">Mokytojai</h3>
          <p class="text-gray-300">
            Kurkite testus, dalinkitės medžiaga ir analizuokite mokinių rezultatus.
          </p>
        </div>
        <!-- Tėvai -->
        <div class="bg-[#1F2333] rounded-lg p-8 text-center shadow-lg hover:shadow-2xl transition-shadow duration-300">
          <div class="mb-4">
            <svg class="w-12 h-12 mx-auto text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M21 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M3 3v2a4 4 0 0 0 3 3.87"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-white mb-2">Tėvai</h3>
          <p class="text-gray-300">
            Stebėkite vaikų pažangą ir gaukite rekomendacijas, kaip geriau juos palaikyti.
          </p>
        </div>
      </section>
  
      <!-- Pagrindinis raginimas veikti (Call to Action) -->
      <section class="mt-20 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">Prisijunk prie mūsų!</h2>
        <p class="text-lg text-gray-300 mb-8">
          Patirk naują mokymosi erą su ILN platforma.
        </p>
        <a href="/register" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-10 rounded-full transition duration-300">
          Registruokis dabar
        </a>
      </section>
    </div>
  </x-layout>
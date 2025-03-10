import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    server: {
        proxy: {
            '/': 'http://localhost:8000',  // Proxy requests to Laravel
          },
        host: '0.0.0.0',
        port: 5174,  // Or whatever port Vite is running on
      },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        outDir: 'public/build',  // Ensure this is correct
        manifest: true,          // Ensure this is enabled
      },
      resolve: {
        alias: {
          '@': path.resolve(__dirname, 'resources'),
        },
      }
});
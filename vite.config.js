import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 'resources/js/app.js',
                'resources/css/mainapp.css', 'resources/js/mainapp.js',
                'resources/css/mapLBS.css', 'resources/js/mapLBS.js',
                'resources/css/mappage.css', 'resources/js/mappage.js',
                'resources/css/map.css', 'resources/js/map.js', 'resources/js/mapadmin.js', 'resources/css/maplogin.css',
                'resources/css/evaluasipage.css', 'resources/js/evaluasipage.js', 'resources/js/edit_evaluasipage.js',
            ],
            refresh: true,
        }),
    ],
});

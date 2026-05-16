import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '~': path.resolve(__dirname, 'node_modules'),
            'bootstrap-icons': path.resolve(__dirname, 'node_modules/bootstrap-icons'),
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `$bootstrap-icons-font-dir: "../../node_modules/bootstrap-icons/font/fonts";`
            }
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['jquery', 'bootstrap', '@popperjs/core'],
                    datatables: [
                        'datatables.net-bs5',
                        'datatables.net-buttons-bs5',
                        'datatables.net-responsive-bs5'
                    ],
                    charts: ['chart.js'],
                }
            }
        },
        chunkSizeWarningLimit: 1000,
    },
    optimizeDeps: {
        include: [
            'jquery',
            'bootstrap',
            '@popperjs/core',
            'sweetalert2',
            'bootstrap-icons',
            'datatables.net-bs5',
            'datatables.net-buttons-bs5',
            'datatables.net-responsive-bs5',
            'chart.js',
            'jszip',
            'pdfmake',
        ],
    },
});

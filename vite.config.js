import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/landing-layout.css",
                "resources/css/catalog.css",
                "resources/css/detail-profile.css",
                "resources/css/detail-struk.css",
                "resources/css/icon-admin.css",
                "resources/css/list-rental.css",
                "resources/css/payment-failed.css",
                "resources/css/payment.css",
            ],
            refresh: true,
        }),
    ],
});

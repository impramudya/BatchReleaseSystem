import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                "resources/css/app.css",
                // 'resources/css/auth.css',
                "resources/js/app.js",
                "resources/js/theme.js",
            ],
            refresh: true,
        }),
    ],
});

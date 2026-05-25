import { defineConfig } from "vite";
import path from "path";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    base: "/refac-employee-app/public/build/",
    // Pre-bundle these deps to avoid resolution issues coming from files
    // located in the sibling monorepo packages.
    optimizeDeps: {
        include: ["jquery", "axios", "@tabler/core", "select2"],
    },
    plugins: [
        laravel({
            input: [
                "resources/assets/frontend.ts",
                "resources/assets/backend.ts",
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: "public/build",
        manifest: "manifest.json",
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: "[name].js",
                chunkFileNames: "chunks/[name].js",
                assetFileNames: ({ name }) => {
                    if (name && /\.(gif|jpe?g|png|svg)$/.test(name)) {
                        return "images/[name].[hash][extname]";
                    } else if (name && /\.css$/.test(name)) {
                        return "css/[name].[hash][extname]";
                    }
                    return "assets/[name].[hash][extname]";
                },
            },
        },
    },
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "192.168.56.1",
        },
        // Allow Vite to serve files from the monorepo packages folder
        // so imports like ../laravel-monorepo/packages/... resolve.
        fs: {
            // include project root and resources so windows absolute paths like
            // E:\...\resources\assets\*.ts/.less are allowed by Vite dev server
            allow: [
                path.resolve(__dirname),
                path.resolve(__dirname, "resources"),
                path.resolve(__dirname, "resources", "assets"),
                path.resolve(__dirname, "node_modules"),
                path.resolve(__dirname, "vendor"),
                // allow the sibling monorepo and its packages (adjust relative path)
                path.resolve(__dirname, "..", "laravel-monorepo"),
                path.resolve(__dirname, "..", "laravel-monorepo", "packages"),
            ],
        },
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
    resolve: {
        alias: {
            // force bare imports from external package files to resolve to this project's node_modules
            bootstrap: path.resolve(
                __dirname,
                "node_modules",
                "bootstrap",
                "dist",
                "js",
                "bootstrap.esm.js",
            ),
            jquery: path.resolve(
                __dirname,
                "node_modules",
                "jquery",
                "dist",
                "jquery.js",
            ),
            "@tabler/core": path.resolve(
                __dirname,
                "node_modules",
                "@tabler",
                "core",
            ),
            // map the exact tabler runtime import used by the package
            "@tabler/core/dist/js/tabler.js": path.resolve(
                __dirname,
                "node_modules",
                "@tabler",
                "core",
                "dist",
                "js",
                "tabler.esm.js",
            ),
        },
    },
});

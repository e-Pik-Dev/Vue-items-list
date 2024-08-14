import {defineConfig} from "vite";
import vue from "@vitejs/plugin-vue";
import svgLoader from "vite-svg-loader";

export default defineConfig({
    plugins: [vue(), svgLoader()],
    build: {
        outDir: "../component",
        rollupOptions: {
            // externalize vue module dependency, so that it won't be bundled
            external: ["vue"],
            output: {
                globals: {
                    vue: "Vue",
                },
                entryFileNames: "[name].js",
                chunkFileNames: "chunk_[name].js",
                assetFileNames: "[name].[ext]",
            },
        },
    },
});

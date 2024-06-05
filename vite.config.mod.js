/* eslint-disable import/no-extraneous-dependencies */
import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue2";
import cssInjectedByJsPlugin from "vite-plugin-css-injected-by-js";
import { resolve } from "node:path";
import istanbul from "vite-plugin-istanbul";
import convertToMixManifest from "./resources/vite2webpack";

const libraryName = "package-slideshow";

export default defineConfig({
  resolve: {
    alias: [
      {
        find: "@",
        replacement: resolve(__dirname, "resources/js"),
      },
    ],
    extensions: [".mjs", ".js", ".vue", ".json"],
  },
  build: {
    sourcemap: false,
    manifest: "manifest1.json",
    target: "esnext",
    outDir: "public", // Set the public directory for output
    emptyOutDir:false,
    rollupOptions: {
      input: ["resources/js/modeler-slideshow-menu.js"],
      output: {
        entryFileNames: "js/[name].js", // Output structure for JS
        chunkFileNames: "js/[name].js",
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith(".css")) return "css/[name][extname]";
          return "assets/[name][extname]";
        },        
      },
    },
  },
  plugins: [
    cssInjectedByJsPlugin({
      relativeCSSInjection: true,
    }),
    vue(),
    convertToMixManifest({
      outDir: resolve(__dirname, "public"),
      baseDir: `vendor/processmaker/packages/${libraryName}`,
      addManifest:"manifest1.json",
    }),
    process.env.NODE_ENV === "test" &&
      istanbul({
        include: ["resources/js/**"],
        exclude: ["node_modules", "test/**/*.js"],
        extension: [".js", ".vue"],
      }),
  ].filter(Boolean),
  server: {
    hmr: {
      host: "localhost",
    },
  },
});

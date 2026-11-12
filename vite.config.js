/* eslint-disable import/no-extraneous-dependencies */
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue2';
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js';
import { resolve } from 'node:path';
import convertToMixManifest from './resources/vite2webpack';

const libraryName = 'package-slideone';

export default defineConfig({
  resolve: {
    alias: [
      {
        find: '@',
        replacement: resolve(__dirname, 'resources/js'),
      },
    ],
    extensions: ['.mjs', '.js', '.vue', '.json'],
  },
  build: {
    sourcemap: true,
    manifest: true,
    target: 'esnext',
    outDir: 'public',
    rollupOptions: {
      external: ['vue', 'Vue', '@processmaker/modeler', 'bootstrap', 'i18n'],
      input: ['resources/js/modeler-slideone-menu.js'],
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) return 'css/[name][extname]';
          return 'assets/[name][extname]';
        },
        globals: {
          vue: 'vue',
          Vue: 'Vue',
          '@processmaker/modeler': 'Modeler',
          bootstrap: 'bootstrap',
          i18n: 'ProcessMaker.i18n',
        },
        format: 'iife',
      },
    },
  },
  plugins: [
    cssInjectedByJsPlugin({
      relativeCSSInjection: true,
    }),
    vue(),
    convertToMixManifest({
      outDir: resolve(__dirname, 'public'),
      baseDir: `vendor/processmaker/packages/${libraryName}`,
    }),
  ],
  server: {
    hmr: {
      host: 'localhost',
    },
  },
});

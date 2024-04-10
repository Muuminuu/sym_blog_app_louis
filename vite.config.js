import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony';
import vuePlugin from "@vitejs/plugin-vue";
/* if you're using React */
// import react from '@vitejs/plugin-react';
import path from 'path';
// node version anterieur à la version 16
// const path = require('path');

// https://vitejs.dev/config/

export default defineConfig({
  root: path.resolve(__dirname, './'),
  resolve: {
    alias: {
      '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
      //   '@': path.resolve(__dirname, './src'),
    }
  },
  plugins: [
    vuePlugin(), 
    symfonyPlugin({
      stimulus: './assets/controllers.json'
    }),
  ],
  build: {
    rollupOptions: {
      input: {
        app: './assets/app.js'
      }
    },
  },
});
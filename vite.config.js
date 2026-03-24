import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
  base: "/theme-build/theme-name/",
  resolve: {
    dedupe: ["alpinejs"],
  },
  plugins: [
    laravel({
      input: [
        "storage/app/themes/theme-name/resources/css/app.css",
        "storage/app/themes/theme-name/resources/js/app.js",
      ],
      publicDirectory: "public",
      buildDirectory: "theme-build/theme-name",
      hotFile: "public/theme-build/theme-name/hot",

      refresh: false,
    }),
  ],

  css: {
    postcss: path.resolve(__dirname, "postcss.config.js"),
  },

  fontFamily: {
    trajan: ["Trajan Pro", "serif"],
    corporate: ["Corporate A", "serif"],
  },

  build: {
    outDir: path.resolve(
      __dirname,
      "../../../../public/theme-build/theme-name"
    ),
    emptyOutDir: false,
    assetsDir: "assets",
  },
});

import path from "path";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

const preset = require("../../../../vendor/filament/filament/tailwind.config.preset");

export default {
  presets: [preset],
  theme: {
    extend: {
      colors: {},
      fontFamily: {
        sans: ["Poppins", "ui-sans-serif", "system-ui", "sans-serif"],
        poppins: ["Poppins", "sans-serif"],
        trenda: ["Trenda", "Poppins", "sans-serif"],
      },
    },
  },
  content: [
    path.resolve(__dirname, "resources/views/**/*.blade.php"),
    path.resolve(__dirname, "resources/js/**/*.js"),
    path.resolve(__dirname, "../../../../resources/views/**/*.blade.php"),
    path.resolve(__dirname, "../../../../storage/app/plugins/**/resources/views/**/*.blade.php"),
    path.resolve(__dirname, "../../../../storage/framework/views/*.php"),
    path.resolve(__dirname, "../../../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php"),
    path.resolve(__dirname, "../../../../vendor/laravel/jetstream/**/*.blade.php"),
  ],
  plugins: [forms, typography],
};

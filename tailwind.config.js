import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "var(--font-sans)",
                    "Roboto",
                    ...defaultTheme.fontFamily.sans,
                ],
                serif: ["var(--font-serif)", ...defaultTheme.fontFamily.serif],
                playfair: ['"Playfair Display"', "var(--font-serif)", "serif"],
                roboto: ['"Roboto"', ...defaultTheme.fontFamily.sans],
                'roboto-condensed': ['"Roboto Condensed"', ...defaultTheme.fontFamily.sans],
                'roboto-semicondensed': ['"Roboto SemiCondensed"', ...defaultTheme.fontFamily.sans],
                khmer: ['"Kantumruy Pro"', "Hanuman", "serif"],
            },
        },
    },

    plugins: [forms],
};

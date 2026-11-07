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
                    "Kantumruy Pro",
                    ...defaultTheme.fontFamily.sans,
                ],
                serif: [
                    "var(--font-serif)",
                    "Hanuman",
                    "Roboto",
                    ...defaultTheme.fontFamily.serif,
                ],
                roboto: ['"Roboto"', ...defaultTheme.fontFamily.sans],
                'roboto-condensed': ['"Roboto Condensed"', ...defaultTheme.fontFamily.sans],
                khmer: ['"var(--font-khmer)"', '"Kantumruy Pro"', '"Hanuman"', '"Roboto"', "sans-serif"],
            },
        },
    },

    plugins: [forms],
};

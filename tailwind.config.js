import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Change 'indigo' to your preferred color (e.g., blue, rose, emerald)
                brand: colors.indigo,
                // Papelo homepage palette
                paper: '#F5F1E6',
                ink: '#22314A',
                teal: '#3F7D6B',
                margin: '#B5514A',
                gold: '#C79A46',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'serif'],
            },
        },
    },

    plugins: [forms],
};

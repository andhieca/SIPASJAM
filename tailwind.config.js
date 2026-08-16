import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'pj-green': {
                    50: '#f2f9f4',
                    100: '#e0f1e5',
                    200: '#c2e3cd',
                    300: '#94cdab',
                    400: '#60b182',
                    500: '#3e9565',
                    600: '#2d774e',
                    700: '#255f40',
                    800: '#204d35',
                    900: '#1b402d',
                },
                'pj-gold': {
                    50: '#fdfbe8',
                    100: '#fcf6c6',
                    200: '#faea90',
                    300: '#f7d850',
                    400: '#f4c321',
                    500: '#e8a910',
                    600: '#c7820a',
                }
            }
        },
    },

    plugins: [forms],
};

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            container: {
                center: true,
                padding: '16px'
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#2563eb',
                lightPrimary: '#60a5fa',
                secondary: '#4b5563',
                success: '#16a34a',
                warning: '#ca8a04',
                danger: '#dc2626',
            }
        },
    },

    plugins: [forms],
};

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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                editorial: {
                    canvas: '#fafaf9',
                    card: '#ffffff',
                    border: '#e7e7e4',
                    borderStrong: '#d1d1cc',
                    ink: '#121212',
                    charcoal: '#1f1f1d',
                    muted: '#71716e',
                    subtle: '#a8a8a3',
                    gold: '#d97706',
                    goldLight: '#fffbeb',
                },
                brand: {
                    50: '#fafaf9',
                    100: '#f5f5f4',
                    200: '#e7e7e4',
                    300: '#d1d1cc',
                    400: '#a8a8a3',
                    500: '#71716e',
                    600: '#1f1f1d',
                    700: '#121212',
                    800: '#0a0a0a',
                    900: '#000000',
                    950: '#000000',
                },
                amber: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
            },
            boxShadow: {
                'subtle': '0 1px 2px rgba(0, 0, 0, 0.04)',
                'card': '0 1px 3px rgba(0, 0, 0, 0.02)',
                'card-hover': '0 8px 24px -4px rgba(0, 0, 0, 0.06)',
                'dropdown': '0 10px 30px -5px rgba(0, 0, 0, 0.08)',
            },
        },
    },

    plugins: [forms],
};

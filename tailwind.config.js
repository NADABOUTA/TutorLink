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
                display: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    950: '#070A10',
                    900: '#0B101B',
                    850: '#0F1626',
                    800: '#131B2E',
                    750: '#18233C',
                    700: '#1E2D4A',
                },
                primary: {
                    50:  '#FCF9F2',
                    100: '#F7F0E1',
                    200: '#EFE1C3',
                    300: '#E4CEA0',
                    400: '#D8B878',
                    500: '#C99F52',
                    600: '#B5863B',
                    700: '#96692B',
                    800: '#7B5224',
                    900: '#64411E',
                },
                amber: {
                    50:  '#fcfaf4',
                    100: '#f8f2e4',
                    200: '#f1e4c7',
                    300: '#e5d1a2',
                    400: '#d8b878',
                    500: '#c99f52',
                    600: '#b5863b',
                    700: '#96692b',
                    800: '#7b5224',
                    900: '#64411e',
                },
                slate: {
                    900: '#0F172A',
                    800: '#1E293B',
                    700: '#334155',
                    600: '#475569',
                    500: '#64748B',
                    400: '#94A3B8',
                    300: '#CBD5E1',
                },
            },
            boxShadow: {
                'xs':       '0 1px 2px 0 rgba(0, 0, 0, 0.4)',
                'soft':     '0 2px 8px 0 rgba(0, 0, 0, 0.5)',
                'card':     '0 4px 20px -2px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255,255,255,0.06)',
                'card-hover': '0 8px 24px -4px rgba(201, 159, 82, 0.1), 0 0 0 1px rgba(201, 159, 82, 0.2)',
                'dropdown': '0 20px 50px -10px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255,255,255,0.08)',
                'modal':    '0 25px 80px -10px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255,255,255,0.08)',
                'glow-sm':  '0 0 12px rgba(201, 159, 82, 0.12)',
                'glow':     '0 0 20px rgba(201, 159, 82, 0.15)',
                'glow-lg':  '0 0 30px rgba(201, 159, 82, 0.2)',
                'glow-amber': '0 0 20px rgba(201, 159, 82, 0.15)',
                'glow-orange': '0 0 20px rgba(181, 134, 59, 0.15)',
            },
        },
    },

    plugins: [forms],
};

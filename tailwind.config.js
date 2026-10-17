import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#fbf2f8',
                    100: '#f6e4f1',
                    200: '#efcbe4',
                    300: '#e4a5d0',
                    400: '#d375b4',
                    500: '#bd4e98',
                    600: '#a3377d',
                    700: '#8a2a69',
                    800: '#6b1c52', // Original primary
                    900: '#581b44',
                    950: '#380a29',
                },
                accent: {
                    50: '#fbf8f0',
                    100: '#f6efdb',
                    200: '#ecdcb5',
                    300: '#dfc287',
                    400: '#d59b34', // Original accent gold
                    500: '#be8325',
                    600: '#a3671d',
                    700: '#7a5410',
                    800: '#643918',
                    900: '#542f17',
                },
                cream: {
                    50: '#ffffff',
                    100: '#fdfbf2', // Original background
                    200: '#f8f1e2',
                    300: '#efe6da', // Original border
                    400: '#ded2c2',
                    900: '#3b2a33', // Original text
                }
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(107, 28, 82, 0.08), 0 4px 6px -2px rgba(107, 28, 82, 0.04)',
                'glow': '0 0 25px -5px rgba(213, 155, 52, 0.35)',
            }
        },
    },
    plugins: [forms],
};

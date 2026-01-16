import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './node_modules/flowbite/**/*.js',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['ui-sans-serif', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        brand: {
          50: '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#6366f1',
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
        },
        ink: '#0B1220',
      },
      boxShadow: {
        soft: '0 10px 30px rgba(11,18,32,0.12)',
        glow: '0 0 0 6px rgba(99,102,241,0.18)',
      },
      borderRadius: {
        xl2: '1.25rem',
      },
    },
  },

  plugins: [forms, require('flowbite/plugin')],
};
    
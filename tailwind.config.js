import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/awcodes/overlook/resources/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            // fontFamily: {
            //     sans: ['Iranyekan'],
            //     iranyekan: ['Iranyekan'],
            // },
            // colors: {
            //     danger: colors.rose,
            //     primary: colors.blue,
            //     success: colors.green,
            //     warning: colors.yellow,
            // },
        },
    },

    plugins: [forms],
};

const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require("tailwindcss/colors")

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js,jsx}',
        './node_modules/vue-tailwind-datepicker/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                dark: {
                    'eval-0': '#151823',
                    'eval-1': '#222738',
                    'eval-2': '#2A2F42',
                    'eval-3': '#2C3142',
                },
                neutral: {
                    100: '#EAECF0',
                    200: '#D0D5DD',
                    300: '#98A2B3',
                    400: '#667085',
                    500: '#465467',
                    600: '#334054',
                    700: '#182230',
                    800: '#101828',
                    900: '#0C111D',
                },
                gray: {
                    100: '#EAECF0',
                    200: '#D0D5DD',
                    300: '#98A2B3',
                    400: '#667085',
                    500: '#465467',
                    600: '#334054',
                    700: '#182230',
                    800: '#101828',
                    900: '#0C111D',
                },
                primary: {
                    100: '#B3CFFF',
                    200: '#85ADFF',
                    300: '#567CFF',
                    400: '#2F4DFF',
                    500: '#000AFF',
                    600: '#0007DB',
                    700: '#0005B7',
                    800: '#000393',
                    900: '#00027A',
                },
                blue: {
                    100: '#B3CFFF',
                    200: '#85ADFF',
                    300: '#567CFF',
                    400: '#2F4DFF',
                    500: '#000AFF',
                    600: '#0007DB',
                    700: '#0005B7',
                    800: '#000393',
                    900: '#00027A',
                },
                success: {
                    100: '#E9FCD8',
                    200: '#CEFAB1',
                    300: '#A9F088',
                    400: '#85E167',
                    500: '#52CE39',
                    600: '#36B129',
                    700: '#1E941C',
                    800: '#127718',
                    900: '#0A6217',
                },
                info: {
                    100: '#CFF2FF',
                    200: '#9FE1FF',
                    300: '#70CBFF',
                    400: '#4CB5FF',
                    500: '#1190FF',
                    600: '#0C6FDB',
                    700: '#0853B7',
                    800: '#053A93',
                    900: '#03297A',
                },
                warning: {
                    100: '#FFFBCC',
                    200: '#FFF699',
                    300: '#FFF066',
                    400: '#FFEA3F',
                    500: '#FFE100',
                    600: '#DBBE00',
                    700: '#B79D00',
                    800: '#937D00',
                    900: '#7A6500',
                },
                error: {
                    100: '#FEE5D6',
                    200: '#FEC5AE',
                    300: '#FD9E86',
                    400: '#FB7967',
                    500: '#F93D36',
                    600: '#D6272F',
                    700: '#B31B2F',
                    800: '#90112C',
                    900: '#770A2B',
                },
                pink: {
                    100: '#FFDCD5',
                    200: '#FFB2AB',
                    300: '#FF8181',
                    400: '#FF6170',
                    500: '#FF2D55',
                    600: '#DB2056',
                    700: '#B71653',
                    800: '#930E4D',
                    900: '#7A0849',
                },
                indigo: '#5856D6',
                cyan: '#32ADE6',
                mint: '#00C7BE',
                "vtd-primary": colors.sky,
                "vtd-secondary": colors.zinc,
            },

            typography: ({ theme }) => ({
                DEFAULT: {
                    css: {
                        '--tw-prose-paragraphs': theme('colors.gray[300]'),
                        '--tw-prose-headings': theme('colors.gray[300]'),
                        '--tw-prose-counters': theme('colors.gray[300]'),
                        '--tw-prose-bold': theme('colors.gray[300]'),
                        h1: {
                            fontSize: '28px',
                            lineHeight: '28px'
                        },
                        h2: {
                            fontSize: '24px',
                            lineHeight: '24px'
                        },
                        h3: {
                            fontSize: '20px',
                            lineHeight: '20px'
                        },
                        p: {
                            fontSize: '14px',
                            lineHeight: '24px'
                        }
                    },
                },
            }),
        },
        fontSize: {
            'xxs': ['10px', '16px'], // or ['0.625rem', '1rem']
            'xs': ['12px', '18px'], // or ['0.75rem', '1.125rem']
            'sm': ['14px', '20px'], // or ['0.875rem', '1.25rem']
            'md': ['16px', '24px'], // or ['1rem', '1.5rem']
            'lg': ['20px', '28px'], // or ['1.25rem', '1.75rem']
            'xl': ['24px', '32px'], // or ['1.5rem', '2rem']
            'xxl': ['30px', '42px'], // or ['1.875rem', '2.625rem']
        }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
}
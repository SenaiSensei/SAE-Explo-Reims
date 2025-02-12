/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        medieval: ['"MedievalSharp"', 'cursive'],
        montserrat: ['"Montserrat"', 'cursive'],
        truculenta: ['"Truculenta"', 'cursive'],
        poppins: ['"Poppins"', 'sans-serif'],
      },
      colors: {
        primary: '#f6eccf',
        pastel: '#c4c4a5',
        light_pastel: '#e8e8cb',
        gold: '#c7aa5f',
        almond_pastel: '#FEF0CB',
        dark_blue: '#0D234B',
        l_dark_blue:'#25395d',
        dark_gold: '#B79629',
        burgundy_red: '#540321',
        l_burgundy_red: '#76354d',
        main_admin: '#1a202e',
        menu_head: '#8da2fb',
        white_smoke: '#efefef',
        p_admin: '#5850ec',
        dark_bg: '#272727',
        dark_cards: '#121212',
      },
      boxShadow: {
        'map-button': '2px 4px 0px 0px rgba(0, 0, 0, 0.77)',
        'itinerary-map-shadow': 'rgba(0, 0, 0, 0.16) 0px 1px 4px',
        'header_admin': '4px 4px 10px rgba(0, 0, 0, .1)',
        'carousel-shadow': 'rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px',
      },
      screens: {
        'max-h700': { 'raw': '(max-height: 700px)' },
        'min-h800': { 'raw': '(min-height: 800px)' },
      },
      height: {
        'mobile-landing-page-height': 'calc(100vh - 8rem)',
        'md-landing-page-height': 'calc(100vh - 4rem)',
        'custom-main-min-height': 'calc(100vh - 128px)',
        'itinerary-description-height': 'calc(100vh - 320px)',
        '18': '70px',
      },
      width: {
        'calc-sidebar': 'calc(100vh - 280px',
        '70': '280px',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
      },
      animation: {
        fadeIn: 'fadeIn 2s ease-in-out',
      },
      spacing: {
        'delay-100': '100ms',
        'delay-200': '200ms',
        'delay-300': '300ms',
        'delay-400': '400ms',
      },
    },
  },
  plugins: [
    require('tailwind-scrollbar')
  ],
  darkMode: "class",
}


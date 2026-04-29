/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.twig',
    './assets/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary:      '#2563eb',
        secondary:    '#0ea5e9',
        accent:       '#f59e0b',
        accentchamp:  '#f09308',
        dark:         '#1e293b',
        light:        '#f8fafc',
        surface:      '#ffffff',
        surfaceHover: '#f1f5f9',
        headrecolor:  '#3a404c',
        footercolor:  '#3a404c',
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'sans-serif'],
      },
    },
  },
  plugins: [],
}

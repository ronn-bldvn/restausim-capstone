// tailwind.config.js
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/View/Components/**/*.php", //for rendering of the CSS of the calendar
    ],

    theme: {
        extend: {},
    },
    plugins: [
        // Your existing custom variants
        function ({ addVariant }) {
            addVariant('sidebar-expanded', '&.sidebar-expanded');
            addVariant('sidebar-expanded-child', '.sidebar-expanded &');
        },
        require('tailwindcss-border-gradient-radius'),

    ],

};

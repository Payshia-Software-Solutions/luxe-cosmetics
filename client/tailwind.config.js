// tailwind.config.js
module.exports = {
    content: [
        './pages/**/*.{js,ts,jsx,tsx}',   // Add paths for your pages
        './components/**/*.{js,ts,jsx,tsx}', // Add paths for your components
        './app/**/*.{js,ts,jsx,tsx}',     // Add paths for your app (if applicable)
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/line-clamp'), // Add line-clamp plugin here
    ],
}

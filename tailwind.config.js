/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // يمكننا إضافة ألوان مخصصة هنا إذا أردنا
      }
    },
  },
  plugins: [],
}

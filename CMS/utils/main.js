function preview() {
    image.src=URL.createObjectURL(event.target.files[0]);
}

const themeToggle = document.getElementById('theme-toggle');
const root = document.querySelector(':root');

// Function to toggle between light and dark theme
function toggleTheme() {
  if (root.classList.contains('dark')) {
    root.classList.remove('dark');
    localStorage.setItem('theme', 'light');
    themeToggle.classList.remove('fa-sun');
    themeToggle.classList.add('fa-moon');
  } else {
    root.classList.add('dark');
    localStorage.setItem('theme', 'dark');
    themeToggle.classList.remove('fa-moon');
    themeToggle.classList.add('fa-sun');
  }
}

// Check local storage for current theme and apply it
const currentTheme = localStorage.getItem('theme');
if (currentTheme === 'dark') {
  root.classList.add('dark');
  themeToggle.classList.remove('fa-moon');
  themeToggle.classList.add('fa-sun');
} else {
    themeToggle.classList.remove('fa-sun');
    themeToggle.classList.add('fa-moon');
}

// Add event listener to theme toggler button
themeToggle.addEventListener('click', toggleTheme);

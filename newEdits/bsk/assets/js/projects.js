// search bar
let lastScroll = 0;
const searchBar = document.getElementById('search-bar');
window.addEventListener('scroll', () => {
  if (window.scrollY > lastScroll) {
    searchBar.classList.add('hide'); // Hide on scroll down
  } else {
    searchBar.classList.remove('hide'); // Show on scroll up
  }
  lastScroll = window.scrollY;
});
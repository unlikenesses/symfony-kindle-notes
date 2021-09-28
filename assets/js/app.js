import '../css/app.scss';
import '@yaireo/tagify/dist/tagify.css';

/* Dropdown menu */
let dropdown = document.querySelector('.dropdown-toggle');
let menu = document.querySelector('.menu');
dropdown.addEventListener('mouseenter', (e) => {
   if (menu.classList.contains('hidden')) {
       menu.classList.remove('hidden');
   }
});
dropdown.addEventListener('mouseleave', (e) => {
   if (! menu.classList.contains('hidden')) {
       menu.classList.add('hidden');
   }
});
/**
 * Initializes the council hub subnav.
 * @constructor
 * @param {Object} el - The council hub subnav element.
 */
function ChubNav(el) {
    var navTrigger = document.querySelector('.js-chub-mobilenav-trigger');

    navTrigger.addEventListener('click', function(){
        el.classList.contains('open') ? el.classList.remove('open') : el.classList.add('open');
    });
  }

  module.exports = ChubNav;

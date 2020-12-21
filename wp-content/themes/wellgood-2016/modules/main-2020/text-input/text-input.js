module.exports = function(el) {
  const input = el.querySelector('input')

  function checkInput () {
    if (input.value.length) {
      input.classList.add('is-dirty')
    } else {
      input.classList.remove('is-dirty')
    }
  }

  if (input) {
    input.addEventListener('change', checkInput)
    input.addEventListener('blur', checkInput)
  }
};

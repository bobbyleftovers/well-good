module.exports = function (el) {
  this.el = el
  this.options = el.querySelectorAll('select option')
  this.select = el.querySelector('select')

  this.select.addEventListener('change', evt => {
    const link = (evt.target.value.indexOf(window.location.protocol) > -1) ? evt.target.value : window.location.protocol + evt.target.value

    if (link !== window.location.href) {
      window.location.href = link
    }
  })
}

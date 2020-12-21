module.exports = function(href) {
  var l = document.createElement('a');
  l.href = href;
  return {
    hostname: l.hostname,
    pathname: l.pathname,
    href: href
  };
}
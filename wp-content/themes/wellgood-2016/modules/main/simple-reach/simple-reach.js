
  function SimpleReach( el ){
    var s = document.createElement('script');
    s.async = true;
    s.type = 'text/javascript';
    s.src = document.location.protocol + '//d8rk54i4mohrb.cloudfront.net/js/reach.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(s);
}

  module.exports = SimpleReach;
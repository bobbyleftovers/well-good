var referrer = window.location.href,
  urlAppend

/**
 * This function will update the page's dataLayer and pass information off to parsely
 *
 * If the fireEvent param is set to false, this function will simply push the contents of the
 * data variable into the datalayer without an event. This condition was added specifically to handle
 * any GTM events that might fire after a user scrolls back up on to an article an infinite-scroll feed.
 *
 * @param Array data - an object of metadata for the page we're on
 * @param bool useEvent - whether or not we should actually fire events on this instance
 */
module.exports = function (datasets, useEvent) {
  // New GTM analytics page view
  if (typeof datasets == 'undefined') {
    datasets = []
  }
  var data = typeof datasets[0] !== 'undefined' ? datasets[0] : {};
  var permutiveInfiniteData = datasets[1];
  if (typeof useEvent == 'undefined') {
    useEvent = true;
  }

  function createUUID() {
    var pow = Math.pow(10, 10);
    var uuid = Math.floor(Math.random() * pow) + '.' + Math.floor(Math.random() * pow);
    return uuid;
  }

  function findPPID() {
    if (!localStorage.getItem('ppid')) {
      ppid = createUUID();
      localStorage.setItem('ppid', ppid);
      return ppid;
    } else {
      return localStorage.getItem('ppid');
    }
  }
  var ppid = findPPID() || '';
  if (typeof data !== 'undefined') {
    data.virtualPageURL = window.location.pathname;
    data.content_name = document.title;
    data.lg_uuid = ppid;
    if (useEvent) {
      data.event = 'VirtualPageview';
    }
  }
  dataLayer.push(data);

  if (typeof permutiveInfiniteData !== 'undefined') {
    permutiveInfiniteData.page.user.lg_uuid = ppid;
    window.permutive.addon('web', permutiveInfiniteData);
  }

  // New Parsely page view
  if (useEvent) {
    if (window.location.href.indexOf('?') === -1) {
      urlAppend = '?'
    } else {
      urlAppend = '&'
    }
    if (window.location.href.slice(-1) != '/') {
      urlAppend = '/' + urlAppend
    }
    PARSELY.beacon.trackPageView({
      'url': window.location.href + urlAppend + 'utm_source=dynamic',
      'urlref': referrer,
      'js': 1
    });
  }

  referrer = window.location.href
}

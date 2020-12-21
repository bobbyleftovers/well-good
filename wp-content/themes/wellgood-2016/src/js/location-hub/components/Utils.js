
/**
 * Dig thumbnail out of post._embedded.wp:featuredmedia[0].media_details.sizes.thumbnail.source_url (thanks WP... ¯\_(ツ)_/¯)
 * @param {object} post Object
 * @param {string} thumbnail size (optional)
 * @return {string} url of post thumbnail
**/
export function getThumbnail(post, size = 'article') {
  
  // this operator is pretty cumbersome at this point -- any cleanup we can do here?
  return (((((((post || {})._embedded || {})['wp:featuredmedia'] || {})[0] || {}).media_details || {}).sizes || {})[size] || {}).source_url || ((((post || {})._embedded || {})['wp:featuredmedia'] || {})[0] || {}).source_url || null 

}

/**
 * Get generic pin icon path
 * @return {string} url of pin path
**/
export function getGenericIcon( border ) {
  if( border ){
    return window.location.protocol + '//' + window.location.host + '/wp-content/themes/wellgood-2016/assets/img/location-hub-pin-default.png'
  }else {
    return window.location.protocol + '//' + window.location.host + '/wp-content/themes/wellgood-2016/assets/img/location-hub-pin-default_no-border.png'    
  }
}

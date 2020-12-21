import { h, render, Component } from 'preact'
import classNames from 'classnames'
import { getThumbnail } from './Utils'

export default class Location extends Component {
  render() {
    const { title, acf, index, default_featured_image } = this.props
    const { address, phone, website, website_display, location_type, logo, description, shop_button_label, shop_button_link } = acf
    const thumbnail = getThumbnail(this.props) || default_featured_image
    const thumbStyle = { backgroundImage: 'url(' + thumbnail + ')'}
    const locationObject = JSON.parse(acf.map_location);
    const locationAddress = locationObject['place_name'];

    // console.log(this.props);

    return (
      <div className="location-hub-sidebar-location-inner">
        {thumbnail && (
          <div className="location-hub-sidebar-location-image-container">
            <div className="image-module" style={ thumbStyle }>
              <img src={thumbnail} className="location-hub-sidebar-location-image image-module-img"/>
            </div>
          </div>
        )}
        <div className="location-hub-sidebar-content">
          <h4 className="location-hub-sidebar-content__headline" dangerouslySetInnerHTML={{__html:  title.rendered  }}/>
          <div className="location-hub-sidebar-content__content" dangerouslySetInnerHTML={{ __html: description }}></div>
          <ul className="location-hub-sidebar-posts-location-info-meta">
            {address && (
              <li className="icon-map-marker">
                <a
                  onClick={() => {
                    // Track event in GTM
                    if ( dataLayer ) {
                      dataLayer.push({
                        'event': 'OutboundLink', 
                        'eventAction': window.location.pathname + 'map', 
                        'eventLabel': 'http://maps.google.com/?q=' + locationAddress
                      });
                    }
                  }}
                  href={'http://maps.google.com/?q=' + locationAddress} target="_blank">{address}</a>
              </li>
            )}
            {phone && <li className="icon-phone">{phone}</li>}
            {website && (
              <li className="icon-external-link">
                <a 
                  onClick={() => {
                    // Track event in GTM
                    if ( dataLayer ) {
                      dataLayer.push({
                        'event': 'OutboundLink',
                        'eventAction': window.location.pathname + 'map',
                        'eventLabel': website 
                      });
                    }
                  }}
                  href={website} target="_blank" rel="nofollow">{website_display ? website_display : website}</a>
              </li>
            )}
          </ul>
          <hr />
          <div className="location-hub-sidebar-content__footer">
            { shop_button_link && (
              <a 
                onClick={() => {
                  // Track event in GTM
                  if ( dataLayer ) {
                    dataLayer.push({
                      'event': 'OutboundLink',
                      'eventAction': window.location.pathname + 'map',
                      'eventLabel': shop_button_link 
                    });
                  }
                }}
                href={ shop_button_link } target="_blank" className="location-hub-sidebar-shoplink btn">
                { shop_button_label ? shop_button_label : 'Shop Now' }</a>
            )}
            { logo && (
              <div className="location-hub-sidebar-logo-wrapper">
                <img src={ logo.url } className="location-hub-sidebar-logo" alt={ logo.title } />
              </div>
            )}    
          </div>
        </div>
      </div>
    )
  }
}


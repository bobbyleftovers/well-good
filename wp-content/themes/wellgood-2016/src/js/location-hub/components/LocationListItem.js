import { h, render, Component } from 'preact'
import { getThumbnail, getGenericIcon } from './Utils'

export default class LocationListItem extends Component {
  render() {
    const { title, acf, index, active, default_featured_image, setActive } = this.props
    const { address, phone, website, website_display, location_type } = acf
    const thumbnail = getThumbnail(this.props) || default_featured_image
    const icon = ((((location_type || {}).acf || {}).icon || {}).sizes || {}).thumbnail || getGenericIcon( false )
    const thumbStyle = { backgroundImage: 'url(' + thumbnail + ')'}
    const locationObject = JSON.parse(acf.map_location);
    const locationAddress = locationObject['place_name'];
    return (
      <div className="location-hub-sidebar-posts-location"
        onClick={($event) => {

          if( $event.target.nodeName !== 'A'){
            setActive( index )
          }

      }}
      data-vars-event="map location sidebar"
      data-vars-info={ title.rendered }>
        <span className="location-hub-angle-icon"></span>
        <div className="location-hub-sidebar-posts-location-info">
          <div className="location-hub-sidebar-posts-location-title" dangerouslySetInnerHTML={{__html: '<h4>' + title.rendered + '</h4>' }}/>
          <ul className="location-hub-sidebar-posts-location-info-meta small">
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
        </div>
        <div className="location-hub-sidebar-posts-location-thumb">
          {thumbnail && (
            <div className="location-hub-sidebar-posts-location-thumb-inner" style={ thumbStyle }>
              <img src={thumbnail} className="location-hub-sidebar-posts-location-thumb-image"/>
            </div>
          )}
          {icon && (
            <img src={icon} className="location-hub-sidebar-posts-location-thumb-icon"/>
          )}
        </div>
      </div>
    )
  }
}

import { h, render, Component } from 'preact'
import classNames from 'classnames'
import zenscroll from 'zenscroll'
import LocationListItem from './LocationListItem'
import Location from './Location'
var $ = jQuery

export default class Sidebar extends Component {
  constructor(props) {
    super(props)
    this.state = {
      active: props.active,
      hasActive: (props.active !== -1),
      mapActive: false
    }


    // this.setActive = this.setActive.bind(this)
    this.updateActive = 0
    this.transitionTime = 1000


  }

  componentWillReceiveProps(props) {
    clearTimeout(this.updateActive)
    let state = this.state

    // Scroll up if active location has changed
    if(state.active !== props.active) {
      this.scroller.toY(0)
    }

    // Update the hasActive state so we know to show/hide
    state.hasActive = (props.active !== -1)

    // Track event in GTM
    // This is a new custom event, will need to be created in GTM to start tracking in analytics
    if ( dataLayer && state.hasActive && this.props.posts[props.active] ) {
      dataLayer.push({
        'event': 'LocationHubOpenLocation', 
        'eventAction': window.location.pathname + 'map', 
        'eventLabel': this.props.posts[props.active].title 
      });
    }

    // Immediately update the active state if not on the list view
    if(state.hasActive) {
      state.active = props.active
    } else {

    // Wait 5 seconds for outro transition to complete from list view before updating the active state
      this.updateActive = setTimeout(() => {
        this.setState({ active: props.active })
      }, this.transitionTime)
    }

    // Update all new states simultaneously 
    this.setState(state)
  }

  componentDidMount() {
    // Setup scroller
    this.scroller = zenscroll.createScroller(this.sidebar, 250, 0)
  }

  // need to switch this over to a state change?
  showTheList(){
    document.querySelector('.location-hub-sidebar-inner').classList.remove('map-open');
    document.querySelector('.location-hub-sidebar-mobile-nav__item--list').classList.add('active');
    document.querySelector('.location-hub-sidebar-mobile-nav__item--map').classList.remove('active');

  //  this.setState({
  //     mapActive: false
  //   });
    
  }
  showTheMap(){
    document.querySelector('.location-hub-sidebar-inner').classList.add('map-open'); 
    document.querySelector('.location-hub-sidebar-mobile-nav__item--list').classList.remove('active');
    document.querySelector('.location-hub-sidebar-mobile-nav__item--map').classList.add('active');
    
    // this.setState({
    //   mapActive: true
    // });
  }

  render() {
    const { posts, types, city, options, setActive } = this.props
    const { active, hasActive } = this.state
    const { location_title, sponsor_text, sponsor_logo } = city
    const sponsor_logo_image = ((sponsor_logo || {}).sizes || {}).medium || false
    const default_featured_image = (((options || {}).featured_image_fallback || {}).sizes || {}).article || '' 
    const className = classNames( 'location-hub-sidebar', { 'open-location': hasActive } )
    const type_icons = types.map(type => (
      ((((type || {}).acf || {}).icon || {}).sizes || {}).thumbnail || false 
    ))
    // console.log('types', types)
    // console.log('posts', posts)
    return (
      <div className={className} ref={ c => this.sidebar = c }>
        <div className="location-hub-sidebar-header">
          <h2>{location_title}</h2>
          {sponsor_logo_image && (
            <div className="location-hub-sidebar-sponsor">
              <h6>{sponsor_text}</h6>
              <img src={sponsor_logo_image} className="location-hub-sidebar-sponsor-image"/>
            </div>
          )}
        </div>
        <div className={ this.state.mapActive ? 'map-open location-hub-sidebar-inner' : 'location-hub-sidebar-inner' }>
          <div className="location-hub-sidebar-inner-content">
            <div className="location-hub-sidebar-mobile-nav">
              <div className="location-hub-sidebar-mobile-nav__item location-hub-sidebar-mobile-nav__item--list active js-mobile-mapbox-nav" onClick={ this.showTheList }>List</div>
              <div className="location-hub-sidebar-mobile-nav__item location-hub-sidebar-mobile-nav__item--map js-mobile-mapbox-nav" onClick={ this.showTheMap }>Map</div>
              {(types.length > 1) && (
                  <div className="location-hub-sidebar-types">
                    {types.map((type, i) => (
                      <h6 key={type.slug + '_' + type.term_id} className="location-hub-sidebar-types-type">
                        {type_icons[i] && (
                          <img src={type_icons[i]} className="location-hub-sidebar-types-type-image"/>
                        )}
                        <span className="location-hub-sidebar-types-title">{type.acf.title}</span>
                      </h6>
                    ))}
                  </div>
                )}
            </div>
            <div className="location-hub-sidebar-list">
              {(types.length > 1) && (
                <div className="location-hub-sidebar-types">
                  {types.map((type, i) => (
                    <h6 key={type.slug + '_' + type.term_id} className="location-hub-sidebar-types-type">
                      {type_icons[i] && (
                        <img src={type_icons[i]} className="location-hub-sidebar-types-type-image"/>
                      )}
                      <span className="location-hub-sidebar-types-title">{type.acf.title}</span>
                    </h6>
                  ))}
                </div>
              )}
              {(posts.length > 1) && (
                <div className="location-hub-sidebar-posts">
                  {posts.map(post => (
                    <LocationListItem 
                      key={post.id} 
                      active={active}
                      default_featured_image={default_featured_image}
                      setActive={setActive}
                      {...post}/>
                  ))}
                </div>
              )}
            </div>
            {posts[active] && (
              <div className="location-hub-sidebar-location">
                <div className="location-hub-sidebar-back"
                  onClick={() => {
                    setActive(-1)
                  }}><span class="location-hub-angle-icon"></span>Back to list</div>
                  <Location {...posts[active]} default_featured_image={default_featured_image}/>
              </div>
            )}
            </div>
          </div>
      </div>
    )
  }
}

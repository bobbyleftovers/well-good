import { h, render, Component } from 'preact'
import { getGenericIcon } from './Utils'
var $ = jQuery

export default class Map extends Component {
  constructor() {
    super()
  }

  componentDidMount() {
    const { types, posts, city, active, setActive } = this.props
    mapboxgl.accessToken = 'pk.eyJ1Ijoid2VsbGFuZGdvb2QiLCJhIjoiY2o2cXl4MG9xMDNmejJxcGg3bWk4NGV0OSJ9.JzPBe78EKXa_6JC5W9s31g'
    // this.map to make accessible on componentupdate
    this.map = new mapboxgl.Map({
      container: 'location-hub-map', // container id
      style: 'mapbox://styles/wellandgood/cj6ry0m8o4ue02smyzhgz9fci', //stylesheet location
      center: [city.default_longitude, city.default_latitude], // starting position
      zoom: parseFloat(city.default_map_zoom || 17) // starting zoom
    })


    // Add navigation control to map
    var navCtrl = new mapboxgl.NavigationControl();
    this.map.addControl(navCtrl, 'bottom-right');

    // Set default icon and location type
    const default_icon = getGenericIcon( true )
    const locationTypes = types.concat([{
      slug: 'default',
      term_id: false,
      acf: {
        icon: default_icon,
        icon_scale: .5,
      }
    }])

    // Group posts into location types (required by mapbox to use different layer per icon)
    const posts_by_type = locationTypes.map( type => {
      let icon = ((((type || {}).acf || {}).icon || {}).sizes || {}).thumbnail || default_icon
      // let icon = '/wp-content/themes/wellgood-2016/src/icon.png'
      let draw_line = type.acf.draw_line || false
      let locations = posts.map( (post, index) => {
        if ((post.acf.location_type.term_id === type.term_id) || (!post.acf.location_type && !type.term_id)) {
          const map_location = JSON.parse( post.acf.map_location )
          const coordinates = map_location.geometry.coordinates.map( loc => parseFloat(loc) )
          return {
            type: 'Feature',
            properties: {
              description: post.title.rendered,
              index: post.index,
            },
            geometry: {
              type: 'Point',
              coordinates: coordinates,
            }
          }
        }
      })

      // Remove empty items
      locations = locations.filter( location => !!location )

      // Prepare coordinates for line drawing
      const coordinates = locations.map(location => location.geometry.coordinates)

      // Get icon scale prop
      const icon_scale = (type.acf && type.acf.icon_scale) ? type.acf.icon_scale : .5

      return {
        name: type.slug,
        locations: locations,
        coordinates: coordinates,
        icon: icon,
        draw_line: draw_line,
        icon_scale: parseFloat(icon_scale),
      }
    })

    // Setup the map with types

    this.map.on('load', function (e) {
      var map = e.target;

      // Loop through each type to add image icon and then locations layer to map
      let image_index = 0
      posts_by_type.map( type => {
        const name = type.name
        const locations = type.locations
        const coordinates = type.coordinates

        map.loadImage(type.icon, (error, image) => {
          map.addImage(name, image)
          image_index++

          // Add the layer for this icon
          map.addLayer({
            id: name,
            type: 'symbol',
            source: {
              type: 'geojson',
              data: {
                type: 'FeatureCollection',
                features: locations,
              },
            },
            layout: {
              'icon-image': name,
              'icon-size': type.icon_scale
            }
          })

          // Add the line layer if the ACF field is set to true
          if( type.draw_line === true ) {
            map.addLayer({
              id: name + '-line',
              type: 'line',
              source: {
                type: 'geojson',
                data: {
                  type: 'FeatureCollection',
                  features: [{
                    type: 'Feature',
                    properties: {
                      color: '#F7455D' // red
                    },
                    geometry: {
                      type: 'LineString',
                      coordinates: coordinates
                    }
                  }]
                }
              },
              paint: {
                'line-width': 3,
                'line-color': '#F7455D'
              }
            })
          }

          // Init the event listeners for this layer
          mapInitEvents( name, map )

        })
      })
    })

    function mapMarkerClick(event) {
      const marker = event.currentTarget.querySelectorAll( '.location-hub-map-marker' )
      const index = marker[0].getAttribute( 'data-index' )
      setActive( index )

      document.querySelector('.location-hub-sidebar-inner').classList.remove('map-open');
      document.querySelector('.location-hub-sidebar-mobile-nav__item--list').classList.add('active');
      document.querySelector('.location-hub-sidebar-mobile-nav__item--map').classList.remove('active');
    }

    function mapInitEvents(type, map) {

      // When a click event occurs on a feature in the places layer, open a popup at the
      // location of the feature, with description HTML from its properties.
      map.on('click', type, function (e) {

        // Remove any existing markers
        $('.mapboxgl-popup').remove();

        // Add marker
        const popup = new mapboxgl.Popup({ offset: 35 })
        .setLngLat(e.features[0].geometry.coordinates)
        .setHTML(
          '<div class="location-hub-map-marker" data-index="' + e.features[0].properties.index + '"><h6 class="location-hub-map-marker__title">' + e.features[0].properties.description + '<span class="location-hub-angle-icon"></span></h6></div>'
        )
        .addTo(map)

        // Marker click listener
        const markers = document.querySelectorAll( '.mapboxgl-popup' )
        if(markers.length) {
          for (let i = 0; i < markers.length; i++) {
            markers[i].addEventListener('click', mapMarkerClick)
          }
        }
      })

      // Change the cursor to a pointer when the mouse is over the places layer.
      map.on('mouseenter', type, function (e) {
        map.getCanvas().style.cursor = 'pointer';
        map.getCanvas().setAttribute("data-vars-event", "map location icon");
        map.getCanvas().setAttribute("data-vars-info", e.features[0].properties.description);
      })

      // Change it back to a pointer when it leaves.
      map.on('mouseleave', type, function () {
        map.getCanvas().style.cursor = '';
        map.getCanvas().removeAttribute("data-vars-event");
        map.getCanvas().removeAttribute("data-vars-info");
      })

      // Force a resize in case the map was already initalized
      map.resize()

    }
  }


  // shouldComponentUpdate() {
  //   return false
  // }

  componentDidUpdate() {


    const { posts, active, city } = this.props

    if( active > -1 ){

      // close existing popups
      closePopups();

      // get coordinates of selected location
      const postMapLocation = JSON.parse(posts[active].acf.map_location)
      const coordinates = postMapLocation.geometry.coordinates

      // display popup
      const popup = new mapboxgl.Popup({ offset: 35 })
      .setLngLat(coordinates)
      .setHTML(
        '<div class="location-hub-map-marker" data-index="' + active + '"><h6 class="location-hub-map-marker__title">' + posts[active].title.rendered + '<span class="location-hub-angle-icon"></span></h6></div>'
      )
      .addTo(this.map)

      // center map on active location
      this.map.flyTo({center: coordinates})
    }else {
      // close existing popup
      closePopups();
    }

    function closePopups(){
      $('.mapboxgl-popup').remove();
    }
  }

  render() {
    return (
      <div id="location-hub-map" class="location-hub-map"></div>
    )
  }
}

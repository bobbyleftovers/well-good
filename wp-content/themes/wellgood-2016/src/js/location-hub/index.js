import { h, render, Component } from 'preact'
import Map from './components/Map'
import Sidebar from './components/Sidebar'
var $ = jQuery

class LocationHub extends Component {
  constructor() {
    super()
    this.state.active = -1
    this.setActive = this.setActive.bind(this)
  }


  setActive( index ) {
    this.setState({ active: parseFloat( index ) })
  }

  render() {
    const props = {
      setActive: this.setActive,
      active: this.state.active,
      types: this.props.types,
      posts: this.props.posts,
      city: this.props.city,
      options: this.props.options,
    }
    return (
      <div class="location-hub">
        <Sidebar {...props}/>
        <Map {...props}/>
      </div>
    )
  }
}

window.locationHubInit = function($el, cityId) {

  function startMap(props) {
    $el.empty()
    const el = $el.get(0)
    render(<LocationHub {...props} />, el)
  }

  let options = false
  let city = false
  let posts = false

  // Get the global options
  $.ajax({
    url: '/wp-json/acf/v2/options/',
    dataType: 'json',
    cache: true
  }).done(data => {
    options = data.acf

    // Get the city info
    $.ajax({
      url: '/wp-json/acf/v2/term/cities/'+cityId,
      dataType: 'json',
      cache: true
    }).done(data => {
      city = data.acf

      // Get the posts
      $.ajax({
        url: '/wp-json/wp/v2/hub-locations?_embed&cities='+cityId+'&location_order=menu_order&order=asc&per_page=100',
        dataType: 'json',
        cache: true
      }).done(data => {
        // Add index to posts
        posts = data.map( (post, index) => {
          post.index = index
          return post
        })

        // Get the location types
        let types = []
        let typeObjs = []

        // Pull location type from posts acf data
        const locationTypes = posts.map( post => post.acf.location_type )

        // Reduce to unique location types
        locationTypes.map(type => {
          const term_id = type ? type.term_id : false
          if(term_id) {
            const term_exists = typeObjs.filter(type => (type.term_id === term_id))
            if(!term_exists.length) {
              typeObjs.push(type)
            }
          }
        })

        // Get type acf data and merge with term data
        typeObjs.map( typeObj => {
          $.ajax({
            url: '/wp-json/acf/v2/term/location-types/'+typeObj.term_id,
            dataType: 'json',
            cache: true
          }).done(data => {
            typeObj.acf = data.acf
            types.push(typeObj)

            // set location type acf data on each post object
            // ensures each post location type comes through with proper acf data to call correct icon
            posts = posts.map( post => {
              if( post.acf.location_type.term_id === typeObj.term_id ){
                post.acf.location_type.acf = data.acf
              }
              return post
            })

            // If last location type start the map
            if (types.length === typeObjs.length) {
              startMap({options, city, types, posts})
            }
          })
        })
      })
    })
  })
}

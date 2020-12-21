(function($){

    function initialize_field( $el ) {

        var mapDOM = $el.find('.mapbox-geojson-map');
        var inputField = $el.find('.mapbox-geojson-field');

        var settings = {
            accessToken: mapDOM.attr('data-access-token'),
            mapId: mapDOM.attr('data-map-id'),
        };

        var setValue = function(value) {
            $(inputField).val(value);
        };

        var jsonData = {
            type: "FeatureCollection",
            features: [],
        };

        try {
            var existingData = JSON.parse($(inputField).val());
            jsonData = existingData
        } catch (err) {
            setValue(JSON.stringify(jsonData));
        }

        if( settings.accessToken ) {

            var existingGeometry = false;
            if(existingData && existingData.geometry && existingData.geometry.coordinates && existingData.geometry.coordinates.length) {
                existingGeometry = existingData.geometry;
            }

            var lat = existingGeometry ? existingGeometry.coordinates[0] : mapDOM.attr('data-lat') ? 
                        parseFloat(mapDOM.attr('data-lat')) : -79.4512
            var long = existingGeometry ? existingGeometry.coordinates[1] : mapDOM.attr('data-long') ? 
                        parseFloat(mapDOM.attr('data-long')) : 43.6568

            mapboxgl.accessToken = settings.accessToken;
            var map = new mapboxgl.Map({
                container: mapDOM[0],
                style: 'mapbox://styles/mapbox/streets-v9',
                center: [lat, long],
                zoom: 14
            });

            var geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken
            });

            map.addControl(geocoder);

            map.on('load', function() {

                map.addSource('single-point', {
                    "type": "geojson",
                    "data": {
                        "type": "FeatureCollection",
                        "features": []
                    }
                });

                map.addLayer({
                    "id": "point",
                    "source": "single-point",
                    "type": "circle",
                    "paint": {
                        "circle-radius": 10,
                        "circle-color": "#007cbf"
                    }
                });

                // Listen for the `geocoder.input` event that is triggered when a user
                // makes a selection and add a symbol that matches the result.
                geocoder.on('result', function(ev) {
                    map.getSource('single-point').setData(ev.result.geometry);
                    setValue(JSON.stringify(ev.result));
                });

                if(existingGeometry) {
                    map.getSource('single-point').setData(existingGeometry);
                }

            });

        }
        else {
            // TODO: display an error in mapDOM
            console.log('mapbox-geojson-err-1');
        }

    }

    if( typeof acf.add_action !== 'undefined' ) {

        /*
        *  ready append (ACF5)
        *
        *  These are 2 events which are fired during the page load
        *  ready = on page load similar to $(document).ready()
        *  append = on new DOM elements appended via repeater field
        *
        *  @type    event
        *  @date    20/07/13
        *
        *  @param   $el (jQuery selection) the jQuery element which contains the ACF fields
        *  @return  n/a
        */

        acf.add_action('ready append', function( $el ){

            // search $el for fields of type 'mapbox_geojson'
            acf.get_fields({ type : 'mapbox_geojson'}, $el).each(function(){

                initialize_field( $(this) );

            });

        });


    } else {


        /*
        *  acf/setup_fields (ACF4)
        *
        *  This event is triggered when ACF adds any new elements to the DOM.
        *
        *  @type    function
        *  @since   1.0.0
        *  @date    01/01/12
        *
        *  @param   event       e: an event object. This can be ignored
        *  @param   Element     postbox: An element which contains the new HTML
        *
        *  @return  n/a
        */

        $(document).on('acf/setup_fields', function(e, postbox){

            $(postbox).find('.field[data-field_type="mapbox_geojson"]').each(function(){

                initialize_field( $(this) );

            });

        });


    }


})(jQuery);

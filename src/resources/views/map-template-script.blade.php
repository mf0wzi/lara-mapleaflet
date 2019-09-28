<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        (function() {
            "use strict";
            let map = L.map('{!! $element !!}', {scrollWheelZoom: false,maxZoom: '{!! $zoom['max'] !!}'}).setView([{!! $location['lat'] !!},{!! $location['long'] !!}],{!! $zoom['start'] !!});
            let basemap = L.tileLayer('{!! $tile["maplink"] !!}', {
                attribution: '{!! $tile["attribution"] !!}'
            }).addTo(map);
            let legend = new L.Control.Customs(null,null,{ collapsed: true }).addTo(map);

            let dataJSON = {!! $datajson !!};
            let defaultMakerInIt = null;
            {!! $marker !!};
            let mapSubType = '{!! $type !!}';
            let markers = null;

            let stateChangingButton = L.easyButton({
                states: [{
                    stateName: 'zoom-to-home',        // name the state
                    icon:      'fa-home',               // and define its properties
                    title:     'zoom to a home',      // like its title
                    onClick: function(btn, map) {       // and its callback
                        map.setView([{!! $location['lat'] !!}, {!! $location['long'] !!}],{!! $zoom['start'] !!});
                    }
                }]
            });
            // create a fullscreen button and add it to the map
            let fullscreen = L.control.fullscreen({
                position: 'topleft', // change the position of the button can be topleft, topright, bottomright or bottomleft, defaut topleft
                title: 'Show me the fullscreen !', // change the title of the button, default Full Screen
                titleCancel: 'Exit fullscreen mode', // change the title of the button when fullscreen is on, default Exit Full Screen
                content: null, // change the content of the button, can be HTML, default null
                forceSeparateButton: true, // force seperate button to detach from zoom buttons, default false
                forcePseudoFullscreen: true, // force use of pseudo full screen even if full screen API is available, default false
                fullscreenElement: false // Dom element to render in full screen, false by default, fallback to map._container
            });

            map.zoomControl.setPosition('topleft');
            //fullscreen.addTo(map);
            map.addControl(fullscreen);
            stateChangingButton.addTo(map);
            stateChangingButton.setPosition('topleft');
            legend.setPosition('bottomleft');

            // detect fullscreen toggling
            map.on('enterFullscreen', function(){
                if(window.console) window.console.log('enterFullscreen');
            });
            map.on('exitFullscreen', function(){
                if(window.console) window.console.log('exitFullscreen');
            });

            if(mapSubType == 'cluster') {
                    markers = L.markerClusterGroup({
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: true,
                    zoomToBoundsOnClick: true,
                    animate1: true,
                    animateAddingMarkers: true
                });
                markers.addTo(map);
            }



            //Object.keys(data)
            dataJSON.forEach(function (data) {

                let defaultMarker = defaultMakerInIt;
                let tooltips = {!! $tooltip !!};  //on hover
                let popups = {!! $popup !!}; // on click
                var title = data.account_name;

                let m = L.marker([data.latitude, data.longitude],{"title":title, "icon":defaultMarker}).bindPopup(popups).bindTooltip(tooltips,
                    {className: "leaflet-label-marker", offset: [0, 0] });
                m.setOpacity(1);
                m.setOpacity(1, true);
                if(mapSubType == 'default'){
                    m.addTo(map);
                } else if(mapSubType == 'cluster'){
                    markers.addLayer(m);
                } else {
                    markers.addLayer(m);
                }
            });

            var search = new L.control.search({
                //url: 'search.php?q={s}',
                layer: markers,
                textPlaceholder: 'Search...',
                position: 'topleft',
                // propertyName: 'Name',
                zoom: 18,
                // initial: false,
                hideMarkerOnCollapse: true,
                // marker: false,
                circleLocation: false
            });

            map.addControl(search);

            search.on('search:collapsed', function(e) {
                map.setView([{!! $location['lat'] !!}, {!! $location['long'] !!}],{!! $zoom['start'] !!});
            });

        })();
    });
</script>

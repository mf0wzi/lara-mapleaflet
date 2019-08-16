<canvas id="{!! $element !!}" width="{!! $size['width'] !!}" height="{!! $size['height'] !!}">
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        (function() {
    		"use strict";
            			let map = L.map('{!! $mapid !!}', {scrollWheelZoom: {!! $scrollWheelZoom !!},maxZoom: {!! $maxZoom !!}).setView([{!! $mapLat !!},{!! $mapLong !!}],{!! $zoom !!});
			let controlCustom = new L.Control.Custom(null,null,{ collapsed: true }).addTo(map);
			let legendCustom = new L.Control.Customs(null,null,{ collapsed: true }).addTo(map);
			let stateChangingButton = L.easyButton({
				states: [{
					stateName: 'zoom-to-home',        // name the state
					icon:      'fa-home',               // and define its properties
					title:     'zoom to a home',      // like its title
					onClick: function(btn, map) {       // and its callback
					     map.setView([{!! $mapLat !!}, {!! $mapLong !!}],{!! $zoom !!});
						 }
						 }]
						 });
					stateChangingButton.addTo(map);
					map.zoomControl.setPosition('topleft');
					map.addControl(new L.Control.Fullscreen().setPosition('topleft'));
					stateChangingButton.setPosition('topleft');
					// informationicon.setPosition('bottomright');
					legend.setPosition('bottomleft');
					
					let osm2 = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {minZoom: 0, maxZoom: 8, attribution: 'Map data &copy; OpenStreetMap contributors'});
					let miniMap = new L.Control.MiniMap(osm2,{toggleDisplay: true, minimized: true }).setPosition('bottomright').addTo(map);
					
					let basemap = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
						attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
						}).addTo(map);
						
					let markers = L.markerClusterGroup({
						spiderfyOnMaxZoom: true,
						showCoverageOnHover: false,
						zoomToBoundsOnClick: true
						});
						var datachange = [];
						markers.addTo(map);
        })();
    });
</script>
</canvas>

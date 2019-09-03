<script id="map-function" type="application/javascript">

function processMarkers(mapdata) {
    mapdata.forEach(function (data) {
        var m = L.marker([data.latitude, data.longitude], {title: title,icon: myIcon});
    });
}


</script>

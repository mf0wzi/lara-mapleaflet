<div id="{!! $element !!}" style="width: auto; height: {!! $size['height'] !!}px; position: relative; overflow: hidden; background-color: transparent;">

</div>
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        (function() {
            "use strict";
            let map = L.map('{!! $element !!}', {scrollWheelZoom: false,maxZoom: 18}).setView([15.387664,47.190976],6.2);
            let basemap = L.tileLayer('{!! $type["maplink"] !!}', {
                attribution: '{!! $type["attribution"] !!}'
            }).addTo(map);
	    
            let datasets = JSON.parse({!! $datasets !!});
	    
            datasets.forEach(function (datas) {
            let tooltips = "<br/>" +
                "<br/>";

            let popup = "<h4>Spot-light</h4><br/>" +
                "<lable><b>Company Name :</b> "+datas.latitude+".</lable><br/>" +
                "<lable><b>Beneficiary Name in Arabic :</b> "+datas.longitude+".</lable><br/>" +
                "<br/>";

                var m = L.marker([datas.latitude, datas.longitude]).bindPopup(popup).bindTooltip(tooltips,
                {className: "leaflet-label-marker", offset: [0, 0] }).addTo(map);

            });
	    
        })();
    });
</script>

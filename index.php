<html>
<head>
<style type="text/css">
#map
{
        height: 100%;
}
html, body
{
        height: 100%;
        margin: 0;
        padding: 0;
}
#info-box
{
        background-color: white;
        border: 1px solid black;
        bottom: 30px;
        height: 20px;
        padding: 10px;
        position: absolute;
        left: 30px;
}
</style>

</head>
<body>
<div id="map"></div>
<div id="info-box">?</div>
<div id="map"></div>

<script>
function makeInfoBox(controlDiv, map)
{

  // Set CSS for the control border.
  var controlUI = document.createElement('div');
  controlUI.style.boxShadow = 'rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px';
  controlUI.style.backgroundColor = '#fff';
  controlUI.style.border = '2px solid #fff';
  controlUI.style.borderRadius = '7px';
  controlUI.style.marginBottom = '32px';
  controlUI.style.marginTop = '10px';
  controlUI.style.textAlign = 'center';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior.
  var controlText = document.createElement('div');
  controlText.style.color = 'rgb(25,25,25)';
  controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
  controlText.style.fontSize = '100%';
  controlText.style.padding = '6px';
  controlText.innerText = 'Heatmap of all connections to our honeypots in the last 24 hours.';
  controlUI.appendChild(controlText);
}


function initMap()
{
var darkstyle = [            {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#263c3f'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#6b9a76'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#38414e'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#212a37'}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#9ca5b3'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#746855'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#1f2835'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#f3d19c'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#2f3948'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#17263c'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#515c6d'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#17263c'}]
            }
        ];

var iconHit = 'http://maps.google.com/mapfiles/kml/pal3/icon47.png';

// init actual map here

var map = new google.maps.Map(document.getElementById('map'), { center: {lat: 0, lng: 0},
zoom: 3,
styles: darkstyle
});
// styles: [{featureType: 'poi', stylers: [{ visibility: 'off' }]}, {featureType: 'transit.station',
//stylers: [{ visibility: 'off' }]}],disableDoubleClickZoom: true, streetViewControl: false});



var infoBoxDiv = document.createElement('div');
var infoBox = new makeInfoBox(infoBoxDiv, map);
infoBoxDiv.index = 1;
map.controls[google.maps.ControlPosition.TOP_CENTER].push(infoBoxDiv);




var geojason = 'http://awebsiteyouown.fart/hitmap.geojson';



map.data.loadGeoJson(geojason);
map.data.loadGeoJson(geojason, null, function (features) {
var markers = features.map(function (feature) {
        var g = feature.getGeometry();
        var marker = new google.maps.Marker({ 'position': g.get(0) });
        return marker;
        });

var markerCluster = new MarkerClusterer(map, markers,{ icon: iconHit, imagePath: 'https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/images/m' });


});

map.data.addListener('mouseover', function(event)
        {
                document.getElementById('info-box').textContent = event.feature.getProperty('ip');
                document.getElementById('info-box').textContent += '   ---   City: ';
                document.getElementById('info-box').textContent += event.feature.getProperty('city');
                document.getElementById('info-box').textContent += '   ---   ISP: ';
                document.getElementById('info-box').textContent += event.feature.getProperty('org_isp');
                document.getElementById('info-box').textContent += '   ---   ASN: ';
                document.getElementById('info-box').textContent += event.feature.getProperty('asn');
                document.getElementById('info-box').textContent += '             --- Services data is inside of the GeoJson. Dig it out yourself. :p ';
//              document.getElementById('info-box').textContent += event.feature.getProperty('services');
        });
}

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=publicapikeythatdoesntmatter&libraries=visualization&callback=initMap"></script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
</body>
</html>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Traffic layer</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: black;
      }
      #map {
        height: 500px;
        width: 500px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>

    <script>
	function initMap() {
	  var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 13,
	    center: {lat: 38.5013985, lng: -77.3880521}
	  });

	  var trafficLayer = new google.maps.TrafficLayer();
	  trafficLayer.setMap(map);
	}

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?&callback=initMap&signed_in=true" async defer>
    </script>
  </body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	  	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<script src="jquery.lettering.js"></script>
	<link rel="stylesheet" href="animate.min.css">
	<script src="jquery.textillate.js"></script>

  	<?php error_reporting(-1); ?>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Dashboard</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: black;
      	font-family: HelveticaCond;
      }
      #map {
        height: 700px;
        width: 600px;

/*      border-radius: 999px;
      -moz-border-radius: 999px;
      -khtml-border-radius: 999px;
      -webkit-border-radius: 999px;*/
      position: absolute;
      bottom: -40px;
      right: -100px;
      }
      #headline {
      	text-align: right;
      }
      #weather {
      	color: white;
      	margin-top: 20px;
      	font-size: 40px;
      }
      h1, h2, h3, h4, h5 {
      	color: white;
      }
      .grey {
      	color: rgb(125, 125, 125);
      }
      #time {
      	font-size: 50px;
      	font-family: HelveticaCond;
      	margin-bottom: 0px;
      	padding-bottom: 0px;
      }
      #date {
      	margin-top: 5px;
      	font-size: 20px;
      }
      @font-face {
	     font-family: Helvetica;
	     src: url(fonts/HelveticaNeue-Thin.otf);
	 }
	 @font-face {
	     font-family: HelveticaCond;
	     src: url(fonts/HelveticaNeue-UltraLight.otf);
	 }
	#viewport {
	    bottom: 0;
	    left: 0;
	    overflow: hidden;
	    perspective: 400; 
	    position: absolute;
	    right: 0;
	    top: 0;

	}

	#world {
	    height: 512px;
	    left: 50%;
	    margin-left: -256px;
	    margin-top: -256px;
	    position: absolute;
	    top: 50%;
	    transform-style: preserve-3d;
	    width: 512px;
	    /*background-color: rgba( 255, 0, 0, .2 );*/
	}
	.cloudBase {
	    height: 20px;
	    left: 256px;
	    margin-left: -10px;
	    margin-top: -10px;
	    position: absolute;
	    top: 256px;
	    width: 20px;
	    /*background-color: rgba( 255, 0, 255, .5 );*/
	}
	.cloudLayer {
	    height: 256px;
	    left: 50%;
	    margin-left: -128px;
	    margin-top: -128px;
	    position: absolute;
	    top: 50%;
	    width: 256px;
	}
	.pos {
		color: green;
	}
	.neg {
		color: red;
	}
	#world img#sun {
		left: 50px;
		top: 40px;
		height: 200px;
		position: absolute;
	}
	#stocks {
		position: absolute;
		bottom: 50px;
		font-size: 20px;
		margin-left: 50px;
		color: white;
		width: 400px;
	}
	#stocks hr {
		margin-top: 5px;
		margin-bottom: 5px;
		margin-left: 15px;
		margin-right: 30px;
	}
	#world img.sunny {
		left: 60px !important;
		top: 75px !important;
		height: 350px !important;
	}
	#moon {
		height: 150px;
		left: 155px;
		top: 100px;
		position: absolute;
	}
	@-webkit-keyframes rotate {
	  from {
	    -webkit-transform: rotate(360deg);
	  }
	  to { 
	    -webkit-transform: rotate(0deg);
	  }
	}
	sup {
		font-size: 55%;
		top: -.8em;
	}

	img#sun
	{
	    -webkit-animation-name:             rotate; 
	    -webkit-animation-duration:         120s; 
	    -webkit-animation-iteration-count:  infinite;
	    -webkit-animation-timing-function: linear;
	}
    </style>
  </head>
  <body>
	<div id="viewport" >
	    <div id="world" >
	    </div>
	</div>
  	<div class="container-fluid">
  		<div class="row">
  			<div class="col-md-5">
	  			<h1 class="grey">DASHBOARD<sup>v0.1</sup></h1>
	  		</div>
	  		<div class="col-md-2">
	  			<h1 class="text-center" id="time">-:--</h1>
	  			<h1 class="text-center" id="date">Friday, August 28</h1>
	  		</div>
	  		<div class="col-md-5">
	  			<h3 id="headline">
					<ul class="texts">
				    </ul>
	  			</h3>
	  		</div>
  		</div>
  		<div class="row">
  			<div class="col-md-5">
  				<h1><a id="lamp">Toggle Lamp</a></h1>
  			</div>
  			<div class="col-md-2 col-md-5">
  				<div id="weather" class="text-center"></div>
  			</div>
  		</div>
  		</div>
	    <div id="map" style="background-color: black;"></div>
		<div id="stocks"></div>
  	</div>
    <script>
    
    /*
	    Defining our variables
	    world and viewport are DOM elements,
	    worldXAngle and worldYAngle are floats that hold the world rotations,
	    d is an int that defines the distance of the world from the camera 
	*/
	/*
	    Event listener to transform mouse position into angles 
	    from -180 to 180 degress, both vertically and horizontally
	*/
	// window.addEventListener( 'mousemove', function( e ) {
	//     worldYAngle = -( .5 - ( e.clientX / window.innerWidth ) ) * 180;
	//     worldXAngle = ( .5 - ( e.clientY / window.innerHeight ) ) * 180;
	//     updateView();
	// } );

	/*
	    Changes the transform property of world to be
	    translated in the Z axis by d pixels,
	    rotated in the X axis by worldXAngle degrees and
	    rotated in the Y axis by worldYAngle degrees.
	*/
	function updateView() {
		var t = 'translateZ( ' + d + 'px ) rotateX( ' + worldXAngle + 'deg) rotateY( ' + worldYAngle + 'deg)';
		world.style.webkitTransform =
		world.style.MozTransform =
		world.style.oTransform = 
		world.style.transform = t;
	}
	
	/*
	    objects is an array of cloud bases
	    layers is an array of cloud layers
	*/

	var layers = [],
		objects = [],
		textures = [],
		
		world = document.getElementById( 'world' ),
		viewport = document.getElementById( 'viewport' ),
		
		d = 0,
		p = 400,
		worldXAngle = 0,
		worldYAngle = 0,
		computedWeights = [];
	viewport.style.webkitPerspective = p;
	viewport.style.MozPerspective = p + 'px';
	viewport.style.oPerspective = p;
	viewport.style.perspective = p;
	textures = [
		{ name: 'white cloud', 	file: 'cloud.png'		, opacity: 1, weight: 0 },
		{ name: 'dark cloud', 	file: 'darkCloud.png'	, opacity: 1, weight: 0 },
		{ name: 'smoke cloud', 	file: 'smoke.png'		, opacity: 1, weight: 0 },
		{ name: 'explosion', 	file: 'explosion.png'	, opacity: 1, weight: 0 },
		{ name: 'explosion 2', 	file: 'explosion2.png'	, opacity: 1, weight: 0 },
		{ name: 'box', 			file: 'box.png'			, opacity: 1, weight: 0 }
	];
	/*
	    Clears the DOM of previous clouds bases 
	    and generates a new set of cloud bases
	*/

	/*
	    Creates a single cloud base: a div in world
	    that is translated randomly into world space.
	    Each axis goes from -256 to 256 pixels.
	*/
	function setTextureUsage( id, mode ) {
		var modes = [ 'None', 'Few', 'Normal', 'Lot' ];
		var weights = { 'None': 0, 'Few': .3, 'Normal': .7, 'Lot': 1 };
		for( var j = 0; j < modes.length; j++ ) {
			if( modes[ j ] == mode ) {
				textures[ id ].weight = weights[ mode ];
			}
		}
	}
	/*
	    Creates a single cloud base and adds several cloud layers.
	    Each cloud layer has random position ( x, y, z ), rotation (a)
	    and rotation speed (s). layers[] keeps track of those divs.
	*/
	function createCloud() {
	
		var div = document.createElement( 'div'  );
		div.className = 'cloudBase';
		var x = 0;
		var y = -10;
		var z = 0;
		var t = 'translateX( ' + x + 'px ) translateY( ' + y + 'px ) translateZ( ' + z + 'px )';
		div.style.webkitTransform = 
		div.style.MozTransform = 
		div.style.oTransform =
		div.style.transform = t;
		world.appendChild( div );
		
		for( var j = 0; j < 7; j++ ) {
			var cloud = document.createElement( 'img' );
			cloud.style.opacity = 0;
			var r = Math.random();
			var src = 'troll.png';
			for( var k = 0; k < computedWeights.length; k++ ) {
				if( r >= computedWeights[ k ].min && r <= computedWeights[ k ].max ) {
					( function( img ) { img.addEventListener( 'load', function() {
						img.style.opacity = .8;
					} ) } )( cloud );
					src = computedWeights[ k ].src;
				}
			}
			cloud.setAttribute( 'src', src );
			cloud.className = 'cloudLayer';
			
			var x = 256 - ( Math.random() * 512 );
			var y = 256 - ( Math.random() * 512 );
			var z = 100 - ( Math.random() * 200 );
			var a = Math.random() * 360;
			var s = .25 + Math.random();
			x *= .2; y *= .2;
			cloud.data = { 
				x: x,
				y: y,
				z: z,
				a: a,
				s: s,
				speed: .1 * Math.random()
			};
			var t = 'translateX( ' + x + 'px ) translateY( ' + y + 'px ) translateZ( ' + z + 'px ) rotateZ( ' + a + 'deg ) scale( ' + s + ' )';
			cloud.style.webkitTransform = 
			cloud.style.MozTransform = 
			cloud.style.oTransform = 
			cloud.style.transform = t;
		
			div.appendChild( cloud );
			layers.push( cloud );
		}
		
		return div;
	}
	function generate() {
		objects = [];
		if ( world.hasChildNodes() ) {
			while ( world.childNodes.length >= 1 ) {
				world.removeChild( world.firstChild );       
			} 
		}
		computedWeights = [];
		var total = 0;
		for( var j = 0; j < textures.length; j++ ) {
			if( textures[ j ].weight > 0 ) {
				total += textures[ j ].weight;
			}
		}
		var accum = 0;
		for( var j = 0; j < textures.length; j++ ) {
			if( textures[ j ].weight > 0 ) {
				var w = textures[ j ].weight / total;
				computedWeights.push( {
					src: textures[ j ].file,
					min: accum,
					max: accum + w
				} );
				accum += w;
			}
		}
		for( var j = 0; j < 1; j++ ) {
			objects.push( createCloud() );
		}
	}


	function update (){
		
		for( var j = 0; j < layers.length; j++ ) {
			var layer = layers[ j ];
			layer.data.a += layer.data.speed;
			var t = 'translateX( ' + layer.data.x + 'px ) translateY( ' + layer.data.y + 'px ) translateZ( ' + layer.data.z + 'px ) rotateY( ' + ( - worldYAngle ) + 'deg ) rotateX( ' + ( - worldXAngle ) + 'deg ) rotateZ( ' + layer.data.a + 'deg ) scale( ' + layer.data.s + ')';
			layer.style.webkitTransform =
			layer.style.MozTransform =
			layer.style.oTransform =
			layer.style.transform = t;
			//layer.style.webkitFilter = 'blur(5px)';
		}
		
		requestAnimationFrame( update );
		
	}
	
	update();

	function initMap() {
	  var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 13,
	    center: {lat: 38.5013985, lng: -77.3880521},
	    heading: 360,
	  });

	  var trafficLayer = new google.maps.TrafficLayer();
	  trafficLayer.setMap(map);
	  var styles = [
	  {
	    stylers: [
	      { hue: "#00ffe6" },
	      { saturation: -20 }
	    ]
	  },{
	    featureType: "road",
	    elementType: "geometry",
	    stylers: [
	      { lightness: 100 },
	      { visibility: "simplified" }
	    ]
	  },{
	    featureType: "road",
	    elementType: "labels",
	    stylers: [
	      { visibility: "on" }
	    ]
	  },{
	    featureType: "lanscape",
	    elementType: "geometry",
	    stylers: [
	      { visibility: "off" }
	    ]
     },{
	    featureType: "lanscape",
	    elementType: "labels",
	    stylers: [
	      { visibility: "off" }
	    ]
	  }
	];

	map.setOptions({styles: styles, disableDefaultUI: true});
	function refreshMap() {
	 	google.maps.event.trigger(map, 'resize');
	 }
	$(document).ready(function() {
	    setInterval(refreshMap, 1000);
	});

	}
    </script>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script>
	  $.ajax({
	  	url: 'getrss.php?q=Google',
	  	type: 'GET',
	  	success: function(data) {
			$('#headline ul').html(data);
      		$(function () {
			    $('#headline').textillate({
				      loop: true,
				      selector: '.texts',
					  minDisplayTime: 3000,
					  
					  in: { effect: 'fadeInDown',
					  	sync: true,
					  	delayScale: 0,
					  	delay: 0,
					   },

					  // out animation settings.
					  out: {
					    effect: 'fadeOutDown',
					    delayScale: 1.5,
					    delay: 50,
					    sync: true,
					    shuffle: false,
					    reverse: false,
					    callback: function () {}
					  },

					  // callback that executes once textillate has finished 
					  callback: function () {},

					  // set the type of token to animate (available types: 'char' and 'word')
					  type: 'word'
					});
				})
	  	},
	  	error: function(err, req) {
			$('#headline ul').html("Error Requesting RSS Feed");
	  	}
	  });
	  var lamp = false;
	  $('#lamp').click(function() {
	  	if ( lamp ) {
		  	$.get("lamp.php?on=1");
	  		lamp = false;
		}
	  	else  {
	  		$.get("lamp.php?on=0");
			lamp = true;  
		}
	  });

	  function updateWeather() {
	  	  $.ajax({
		  	url: 'getrss.php?q=weather',
		  	type: 'GET',
		  	success: function(data) {
		  		parsed = data.split(',');
		  		temp = parsed[0];
		  		code = parsed[1];
		      	$('#weather').html(temp);
		      	if ( (code >= 34 && code <= 35) || (code >=26 && code <=30) ) {
	  				setTextureUsage( 0, 'Lot' );
					setTextureUsage( 1, 'None' );
					setTextureUsage( 2, 'None' );
					setTextureUsage( 3, 'None' );
					setTextureUsage( 4, 'None' );
					setTextureUsage( 5, 'None' );
					generate();
					$('#world').append('<img src="light.png" id="sun">');
		      	}
		      	else if( (code >= 0 && code <= 12) || (code >=37 && code <= 40) ) {
		      		setTextureUsage( 0, 'None' );
					setTextureUsage( 1, 'None' );
					setTextureUsage( 2, 'Lot' );
					setTextureUsage( 3, 'None' );
					setTextureUsage( 4, 'None' );
					setTextureUsage( 5, 'None' );
					generate();
		      	}
		      	else if ( code == 32 ) {
					$('#world').append('<img src="light.png" id="sun" class="sunny">');
		      	}
		      	else if ( code == 33 ) {
		      		$('#world').append('<img src="moon.png" id="moon">');
		      	}

		  	},
		  	error: function(err, req) {
				$('#weather').html("Error Requesting RSS Feed");
		  	}
		  });
	  }

	$.ajax({
	  	url: 'getrss.php?q=stocks',
	  	type: 'GET',
	  	success: function(data) {
	      	$('#stocks').html(data);
	  	},
	  	error: function(err, req) {
			$('#stocks').html("Error Requesting RSS Feed");
	  	}
	  });

		function updateClock ( ) {
		 	var currentTime = new Date ( );

		  	var currentHours = currentTime.getHours ( );
		  	var currentMinutes = currentTime.getMinutes ( );
		  	var currentSeconds = currentTime.getSeconds ( );

		  	// Pad the minutes and seconds with leading zeros, if required
		  	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
		  	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

		  	// Choose either "AM" or "PM" as appropriate
		  	var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

		  	// Convert the hours component to 12-hour format if needed
		  	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

		  	// Convert an hours component of "0" to "12"
		  	currentHours = ( currentHours == 0 ) ? 12 : currentHours;

		  	// Compose the string for display
		  	var currentTimeString = currentHours + ":" + currentMinutes;//+ ":" + currentSeconds + " " + timeOfDay;
		  	
		  	
		   	$("#time").text(currentTimeString);
		   	  	
		 }

		 function updateDate() {
		 	var currentTime = new Date ( );
		 	var month = currentTime.getMonth()+1;
			var date = currentTime.getDate();
			var day = currentTime.getDay();

			var monthNames = ["January", "February", "March", "April", "May", "June",
			  "July", "August", "September", "October", "November", "December"
			];
			var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

			var date = days[day] + ", " + monthNames[month-1] + " " + date;
			$("#date").text(date);
		 }

		 updateClock();
		 updateDate();
		 updateWeather();
		$(document).ready(function()
		{
		   setInterval('updateClock()', 1000);
		   setInterval('updateDate()', 1000000);
		   setInterval('showRSS("cnn")',100000);
		   setInterval('updateWeather()',10000);
		});

	</script>



    <script src="https://maps.googleapis.com/maps/api/js?&callback=initMap&signed_in=true" async defer>
    </script>
  </body>
</html>

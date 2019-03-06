/* <![CDATA[ */


// Jquery validate form contact
jQuery(document).ready(function(){
	
	$('#contactform').submit(function(){

		var action = $(this).attr('action');

		$("#message-contact").slideUp(750,function() {
		$('#message-contact').hide();

 		$('#submit-contact')
			.after('<i class="icon-spin4 animate-spin loader"></i>')
			.attr('disabled','disabled');
			
		$.post(action, {
			name_contact: $('#name_contact').val(),
			lastname_contact: $('#lastname_contact').val(),
			email_contact: $('#email_contact').val(),
			phone_contact: $('#phone_contact').val(),
			message_contact: $('#message_contact').val(),
			verify_contact: $('#verify_contact').val()
		},
			function(data){
				document.getElementById('message-contact').innerHTML = data;
				$('#message-contact').slideDown('slow');
				$('#contactform .loader').fadeOut('slow',function(){$(this).remove()});
				$('#submit-contact').removeAttr('disabled');
				if(data.match('success') != null) $('#contactform').slideUp('slow');

			}
		);

		});
		return false;
	});
});

// Jquery validate form register doctor
jQuery(document).ready(function(){
	
	$('#register_doctor').submit(function(){

		var action = $(this).attr('action');

		$("#message-register").slideUp(750,function() {
		$('#message-register').hide();

 		$('#submit-register')
			.after('<i class="icon-spin4 animate-spin loader"></i>')
			.attr('disabled','disabled');
			
		$.post(action, {
			name_register: $('#name_register').val(),
			lastname_register: $('#lastname_register').val(),
			specialization: $('#specialization').val(),
			city_register: $('#city_register').val(),
			country_register: $('#country_register').val(),
			address_register: $('#address_register').val(),
			mobile_register: $('#mobile_register').val(),
			office_phone_register: $('#office_phone_register').val(),
			email_register: $('#email_register').val(),
			verify_register: $('#verify_register').val()
		},
			function(data){
				document.getElementById('message-register').innerHTML = data;
				$('#message-register').slideDown('slow');
				$('#register_doctor .loader').fadeOut('slow',function(){$(this).remove()});
				$('#submit-register').removeAttr('disabled');
				if(data.match('success') != null) $('#register_doctor').slideUp('slow');

			}
		);

		});
		return false;
	});
});

// Jquery validate form booking visit
jQuery(document).ready(function(){
	
	$('#booking').submit(function(){

		var action = $(this).attr('action');

		$("#message-booking").slideUp(750,function() {
		$('#message-booking').hide();

 		$('#submit-booking')
			.after('<i class="icon-spin4 animate-spin loader"></i>')
			.attr('disabled','disabled');
			
		$.post(action, {
			doctor_name_booking: $('#doctor_name_booking').val(),
			name_booking: $('#name_booking').val(),
			lastname_booking: $('#lastname_booking').val(),
			email_booking: $('#email_booking').val(),
			booking_date: $('#booking_date').val(),
			booking_time: $('#booking_time').val(),
			booking_visit: $('#booking_visit').val(),
			booking_message: $('#booking_message').val(),
			verify_booking: $('#verify_booking').val()
		},
			function(data){
				document.getElementById('message-booking').innerHTML = data;
				$('#message-booking').slideDown('slow');
				$('#booking .loader').fadeOut('slow',function(){$(this).remove()});
				$('#submit-booking').removeAttr('disabled');
				if(data.match('success') != null) $('#booking').slideUp('slow');

			}
		);

		});
		return false;
	});
});
/* ]]> */
(function($){
	$.fn.mapmarker = function(options){
		var opts = $.extend({}, $.fn.mapmarker.defaults, options);

		return this.each(function() {
			// Apply plugin functionality to each element
			var map_element = this;
			addMapMarker(map_element, opts.zoom, opts.center, opts.markers);
		});
	};

	// Set up default values
	var defaultMarkers = {
		"markers": []
	};

	$.fn.mapmarker.defaults = {
		zoom	: 8,
		center	: 'United States',
		markers	: defaultMarkers,
	}

	// Main function code here (ref:google map api v3)
	function addMapMarker(map_element, zoom, center, markers){
		//console.log($.fn.mapmarker.defaults['center']);

		//Set center of the Map
		var myOptions = {
		  zoom: zoom,
		  scrollwheel: true,
		   styles:[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}],
		  mapTypeControl: false,
		  streetViewControl: false,
		  draggable: true,
		  zoomControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.LARGE,
					position: google.maps.ControlPosition.RIGHT_TOP
				},
		  mapTypeId: google.maps.MapTypeId.ROADMAP

		}
		var map = new google.maps.Map(map_element, myOptions);
		var geocoder = new google.maps.Geocoder();
		var infowindow = null;
		var baloon_text = "";

		var geocodeParam = { };

		if(typeof center == "string"){
			geocodeParam = { 'address': center };
		}
		else{
			geocodeParam = { 'location': center};
		}

		//geocoder.geocode( { 'address': center}, function(results, status) {
		geocoder.geocode( { 'location': center}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				//In this case it creates a marker, but you can get the lat and lng from the location.LatLng
				map.setCenter(results[0].geometry.location);
			}
			else{
				console.log("Geocode was not successful for the following reason: " + status);
			}
		});

		//run the marker JSON loop here
		$.each(markers.markers, function(i, the_marker){
			latitude=the_marker.latitude;
			longitude=the_marker.longitude;
			icon=the_marker.icon;
			var baloon_text=the_marker.baloon_text;

			if(latitude!="" && longitude!=""){
				var marker = new google.maps.Marker({
					map: map, 
					position: new google.maps.LatLng(latitude,longitude),
					animation: google.maps.Animation.DROP,
					icon: icon,
					title: 'Allô Docteur',
				});

			}
		});
	}

})(jQuery);

		//set up markers 
		var myMarkers = {
			markers: [
				{
					latitude:5.331017,
					longitude:-3.998667,
					icon: "../../img/map-marker-contacts.png"
				}
			],
		};
		
		//set up map options
		$("#map_contact").mapmarker({
			zoom	: 14,
			markers	: myMarkers,
			center	: {lat:5.331017, lng:-3.998667}
		});


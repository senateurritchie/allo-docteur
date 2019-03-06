
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


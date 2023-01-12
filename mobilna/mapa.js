function pokaz_mapa_pogody()
{
	$('#wykresy').fadeOut();
	$('#Pogoda_burze').fadeOut();
	$('#f_zmien_pogode').fadeIn();
}

function schowaj_mapa_pogody()
{
	$('#f_zmien_pogode').fadeOut();
		$('#Pogoda_burze').fadeIn();
	$('#wykresy').fadeIn();
}



    // USTAWIENIA MAPY
	function initMap()
	{
		var map = new google.maps.Map(document.getElementById('mapa_pogody'), {
			center: new google.maps.LatLng(52.48, 19.07),
      zoom: 6
	  });
			var liczba_znacznikow=0;
		function addMarker(location, map)
		{
      //POBRANIE WARTOŚCI Z FORMLARZA

      if (liczba_znacznikow<1)
      {
        // DODAJE MARKER W MIEJSCU KLIKNIĘCIA NA MAPIE
  			var marker = new google.maps.Marker({
  				position: location,
          draggable: true,
  				map: map
  			});
        //POBRANIE LOKALIZACJI
        var lat = marker.position.lat();
        var lng = marker.position.lng();
				liczba_znacznikow++;

        //WYSYŁA DANE MARKER
        $.ajax({
          type:"POST",
          url:"zmien_latlng.php",

          data: {lat:lat,lng:lng},
        });

        marker.addListener('click', function() {

          $.ajax({
            type:"POST",
            url:"usun.php",

            data: {lat:lat,lon:lng},

          });
           marker.setMap();
           liczba_znacznikow--;
        });

      }

		}


		// WYWOŁANIE FUNKCJI addMarker() PRZY KLIKNĘCIU NA MAPE
	 google.maps.event.addListener(map, 'click', function(event) {
		 addMarker(event.latLng, map);
	 });
	  var infoWindow = new google.maps.InfoWindow;



	}



	function doNothing() {}

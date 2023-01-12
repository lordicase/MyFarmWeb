<script>
////rodzaje znaczników

  var customLabel =
  {
    Zbożowe:
    {
      icon: '../img/zielony.png'
    },
    Groch:
    {
      icon: '../img/błękitny.png'
    },
    Łubin:
    {
      icon: '../img/czerwony.png'
    },
    Bobik:
    {
      icon: '../img/fioletowy.png'
    },
    Kukurydza:
    {
      icon: '../img/niebieski.png'
    },
    Ziemniaki:
    {
      icon: '../img/pomarańczowy.png'
    },
    Buraki:
    {
      icon: '../img/żółty.png'
    },
    Rzepak:
    {
      icon: '../img/różowy.png'
    }
  }



    // zabezpieczenie przed stawianiem dużej ilośći znaczników
  var liczba_znacznikow = 0;


    // USTAWIENIA MAPY
	function initMap()
	{
		var map = new google.maps.Map(document.getElementById('map'), {
      center: new google.maps.LatLng(52.48, 19.07),
      zoom: 6,
      mapTypeId: 'hybrid'
	  });

		function addMarker(location, map)
		{
      //POBRANIE WARTOŚCI Z FORMLARZA
      var uprawa = document.getElementById("uprawa").value;
      var szkodnik =  document.getElementById("szkodnik").value;
      if ((uprawa !='0')&&(szkodnik !='0')&&(liczba_znacznikow<=5))
      {
        // DODAJE MARKER W MIEJSCU KLIKNIĘCIA NA MAPIE
  			var marker = new google.maps.Marker({
  				position: location,
          draggable: true,
  				map: map
  			});
        //POBRANIE LOKALIZACJI
        var lat = marker.position.lat();
        var long = marker.position.lng();


        //WYSYŁA DANE MARKER
        $.ajax({
          type:"POST",
          url:"dodaj_s.php",

          data: {lat:lat,lon:long,uprawa:uprawa,szkodnik:szkodnik},
        });

        marker.addListener('click', function() {

          $.ajax({
            type:"POST",
            url:"usun.php",

            data: {lat:lat,lon:long},

          });
           marker.setMap();
           liczba_znacznikow--;
        });
      liczba_znacznikow++;
      }
		}


		// WYWOŁANIE FUNKCJI addMarker() PRZY KLIKNĘCIU NA MAPE
	 google.maps.event.addListener(map, 'click', function(event) {
		 addMarker(event.latLng, map);
	 });
	  var infoWindow = new google.maps.InfoWindow;

		// POBIERANIE DANYCH O ZNACZNIKACH Z XML
		downloadUrl('pobieranie_d.php', function(data) {
			var xml = data.responseXML;
			var markers = xml.documentElement.getElementsByTagName('marker');
			Array.prototype.forEach.call(markers, function(markerElem) {
				var id = markerElem.getAttribute('id');
				var name = markerElem.getAttribute('name');
				var address = markerElem.getAttribute('address');
				var type = markerElem.getAttribute('type');
				var point = new google.maps.LatLng(
						parseFloat(markerElem.getAttribute('lat')),
						parseFloat(markerElem.getAttribute('lng')));

				var infowincontent = document.createElement('div');
				var strong = document.createElement('strong');
				strong.textContent = name
				infowincontent.appendChild(strong);
				infowincontent.appendChild(document.createElement('br'));

				var text = document.createElement('text');
				text.textContent = address
				infowincontent.appendChild(text);
				var icon = customLabel[type] || {};
         if ($("#"+type).is(':checked')) {
				var marker = new google.maps.Marker({
					map: map,
					position: point,
					label: icon.label,
          icon: icon.icon
				});

				marker.addListener('click', function() {
					infoWindow.setContent(infowincontent);
					infoWindow.open(map, marker);
				});
         }
			});

		});
	}



	function downloadUrl(url, callback) {
		var request = window.ActiveXObject ?
				new ActiveXObject('Microsoft.XMLHTTP') :
				new XMLHttpRequest;

		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				request.onreadystatechange = doNothing;
				callback(request, request.status);
			}
		}

		request.open('GET', url, true);
		request.send(null);
	}

	function doNothing() {}




</script>

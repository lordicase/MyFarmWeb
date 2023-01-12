
  function pokaz_f_dodaj_d()
  {
    $('#f_dodaj_d').fadeIn();

  }

  function schowaj_f()
  {
    $('#f_zmien_d').fadeOut();
    $('#f_dodaj_d').fadeOut();
  }



// This example creates a simple polygon representing the Bermuda Triangle.
// When the user clicks on the polygon an info window opens, showing
// information about the polygon's coordinates.

var map;
var infoWindow;

function initMap() {
  map = new google.maps.Map(document.getElementById('map_dzialki'), {
    center: new google.maps.LatLng(52.48, 19.07),
    zoom: 6,
    mapTypeId: 'hybrid'
  });

  function addMarker(location, map)
  {
    //POBRANIE WARTOŚCI Z FORMLARZA

    if (document.getElementById("user_id").value !='0')
    {
      var id_dzialki = document.getElementById("id_dzialki").value;
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
        url:"dodaj_ksztalt.php",

        data: {lat:lat,lon:long,id_dzialki:id_dzialki},
      });
    }
  }


  // WYWOŁANIE FUNKCJI addMarker() PRZY KLIKNĘCIU NA MAPE
 google.maps.event.addListener(map, 'click', function(event) {
   addMarker(event.latLng, map);
 });


  // POBIERANIE DANYCH O ZNACZNIKACH Z XML
  downloadUrl('pobieranie_ksztaltu.php', function(data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName('marker');
    Array.prototype.forEach.call(markers, function(markerElem) {
      var user_id = markerElem.getAttribute('user_id');
      var id_dzialki = markerElem.getAttribute('id_dzialki');
      var ile_znacznikow = markerElem.getAttribute('ile_znacznikow');
      var nazwa_w = markerElem.getAttribute('nazwa_w');
      var uprawa = markerElem.getAttribute('uprawa');

      // Define the LatLng coordinates for the polygon.
        var triangleCoords = [];
        for (var i = 0; i < ile_znacznikow; i++) {
          triangleCoords[i]=new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat'+i)),
                parseFloat(markerElem.getAttribute('lng'+i)));
        }

      var dzialka = new google.maps.Polygon({
        paths: triangleCoords,
        label: 'kot',
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        nazwa_w: nazwa_w,
        uprawa: uprawa
      });

dzialka.setMap(map);
// Add a listener for the click event.
dzialka.addListener('click', showArrays);

infoWindow = new google.maps.InfoWindow;
    });

  });
}

/** @this {google.maps.Polygon} */
function showArrays(event) {

  var contentString = '<b>Działka:<br>'+this.nazwa_w+'</b><br>' +
      'Uprawa: <br>' + this.uprawa;


  // Replace the info window's content and position.
  infoWindow.setContent(contentString);
  infoWindow.setPosition(event.latLng);

  infoWindow.open(map);
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

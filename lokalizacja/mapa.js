
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
    center: new google.maps.LatLng(53.167985, 19.059277),
    zoom: 12
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

      // marker.addListener('click', function() {
      //
      //   $.ajax({
      //     type:"POST",
      //     url:"usun.php",
      //
      //     data: {lat:lat,lon:long},
      //
      //   });
      //    marker.setMap();
      //
      // });

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
//      var address = markerElem.getAttribute('address');
//      var type = markerElem.getAttribute('type');
      var ile_znacznikow = markerElem.getAttribute('ile_znacznikow');


      // Define the LatLng coordinates for the polygon.
        var triangleCoords = [];
        for (var i = 0; i < ile_znacznikow; i++) {
          triangleCoords[i]=new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat'+i)),
                parseFloat(markerElem.getAttribute('lng'+i)));
        }

      var bermudaTriangle = new google.maps.Polygon({
        paths: triangleCoords,
        label: 'kot',
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: '#FF0000',
        fillOpacity: 0.35
      });

bermudaTriangle.setMap(map);
// Add a listener for the click event.
bermudaTriangle.addListener('click', showArrays);

infoWindow = new google.maps.InfoWindow;
    });

  });
}

/** @this {google.maps.Polygon} */
function showArrays(event) {
  // Since this polygon has only one path, we can call getPath() to return the
  // MVCArray of LatLngs.
  var vertices = this.getPath();

  var contentString = '<b>Bermuda Triangle polygon</b><br>' +
      'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
      '<br>';

  // Iterate over the vertices.
  for (var i =0; i < vertices.getLength(); i++) {
    var xy = vertices.getAt(i);
    contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
        xy.lng();
  }

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

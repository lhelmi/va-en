<!DOCTYPE html>
<html>
  <head>
    <title>Add Map</title>
    <script
      src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GmapsAPI') }}&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
    <style>
        /* Set the size of the div element that contains the map */
        #map {
            height: 400px;
            /* The height is 400 pixels */
            width: 100%;
            /* The width is the width of the web page */
        }
    </style>
  </head>
  <body>
    <h3>My Google Maps Demo</h3>
    <!--The div element for the map -->
    <div id="map"></div>
  </body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
let map;
let markers = [];

function initMap() {
  const haightAshbury = { lat : -6.91757808164908, lng : 107.60850421142572 };
  
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 12,
    center: haightAshbury,
    mapTypeId: "terrain",
  });
  // This event listener will call addMarker() when the map is clicked.
  map.addListener("click", (event) => {
    deleteMarkers();
    addMarker(event.latLng);
    const lat = event.latLng.lat();
    const lng = event.latLng.lng();
    const latlng = lat + lng;
    $.ajax({
      url: "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key={{ getenv('GmapsAPI') }}",
      type: 'POST',
      data: JSON,
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        // console.log(response.results[0].address_components);
        var iniindex = 0;
        for (let index = 0; index < response.results[0].address_components.length; index++) {
          const results = response.results[0].address_components[index].types;
          // console.log(results);
          for (let indexx = 0; indexx < results.length; indexx++) {
            const element = results[indexx];
            // console.log(element);
            if(element == 'postal_code'){
              iniindex = iniindex+index;
            }
          }
        }
        for (let index = 0; index < response.results[0].address_components.length; index++) {
          const postal_code = response.results[0].address_components[iniindex].long_name;
          console.log('postal_code : ' + postal_code);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          alert('Error adding / update data');
          $('#btnSimpan').text('Simpan');
          $('#btnSimpan').attr('disabled', false);
      }
    });
  });
  // Adds a marker at the center of the map.
  addMarker(haightAshbury);
}

// Adds a marker to the map and push to the array.
function addMarker(location) {
  const marker = new google.maps.Marker({
    position: location,
    map: map,
  });
  markers.push(marker);
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}
</script>
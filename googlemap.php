<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Map Locations</title>

  <style>
    body {
      margin: 0;
      padding: 0;
    }

    #map {
      height: 600px;
      width: 100%;
    }
  </style>
</head>

<body>

<div id="map"></div>

<script>

function initMap() {

  const centerLocation = {
    lat: -37.8136,
    lng: 144.9631
  };

  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 9,
    center: centerLocation
  });

  const locations = [

    { name: "Rye", lat: -38.3853, lng: 144.8122 },
    { name: "Rosebud", lat: -38.3556, lng: 144.9068 },
    { name: "Dromana", lat: -38.3338, lng: 144.9646 },
    { name: "Mount Eliza", lat: -38.1889, lng: 145.0920 },
    { name: "McCrae", lat: -38.3497, lng: 144.9272 },
    { name: "Hastings", lat: -38.3059, lng: 145.1896 },
    { name: "Mornington", lat: -38.2179, lng: 145.0388 },
    { name: "Mount Martha", lat: -38.2667, lng: 145.0167 },
    { name: "Safety Beach", lat: -38.3151, lng: 144.9918 },
    { name: "Somerville", lat: -38.2177, lng: 145.1741 },
    { name: "Blairgowrie", lat: -38.3613, lng: 144.7795 },
    { name: "Tootgarook", lat: -38.3775, lng: 144.8500 },
    { name: "Balnarring", lat: -38.3734, lng: 145.1235 },
    { name: "Tyabb", lat: -38.2608, lng: 145.1864 },

    { name: "Glen Waverley", lat: -37.8781, lng: 145.1648 },
    { name: "Wheelers Hill", lat: -37.9050, lng: 145.1890 },
    { name: "Mount Waverley", lat: -37.8770, lng: 145.1290 },
    { name: "Toorak", lat: -37.8417, lng: 145.0170 },
    { name: "Doncaster", lat: -37.7861, lng: 145.1230 },
    { name: "Donvale", lat: -37.7896, lng: 145.1748 },
    { name: "Box Hill", lat: -37.8189, lng: 145.1253 },
    { name: "Burwood", lat: -37.8500, lng: 145.1140 },
    { name: "Vermont", lat: -37.8362, lng: 145.1960 },
    { name: "Camberwell", lat: -37.8427, lng: 145.0694 },

    { name: "Carrum Downs", lat: -38.0997, lng: 145.1724 },
    { name: "Chelsea Heights", lat: -38.0333, lng: 145.1333 },
    { name: "Patterson Lakes", lat: -38.0690, lng: 145.1430 },
    { name: "Skye", lat: -38.1051, lng: 145.2165 },
    { name: "Langwarrin", lat: -38.1535, lng: 145.1863 },
    { name: "Seaford", lat: -38.1049, lng: 145.1335 },
    { name: "Aspendale", lat: -38.0293, lng: 145.1026 },
    { name: "Keysborough", lat: -37.9910, lng: 145.1738 },

    { name: "Sandringham", lat: -37.9500, lng: 145.0000 }

  ];

  locations.forEach(location => {

    const marker = new google.maps.Marker({
      position: {
        lat: location.lat,
        lng: location.lng
      },
      map: map,
      title: location.name
    });

    const infoWindow = new google.maps.InfoWindow({
      content: `<h3>${location.name}</h3>`
    });

    marker.addListener("click", () => {
      infoWindow.open(map, marker);
    });

  });

}

</script>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC_r5F1eC4o7ct4filjaurPn1Zxcre_Kk&callback=initMap">
</script>

</body>
</html>
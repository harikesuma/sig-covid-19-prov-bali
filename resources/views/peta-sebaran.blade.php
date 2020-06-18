<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Peta Sebaran Per Kelurahan/Desa</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>

     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin="">
    </script>

    <!-- Leaflet-KMZ -->
    <script src="https://unpkg.com/leaflet-kmz@latest/dist/leaflet-kmz.js"></script>

</head>
<body>
  <div class="pos-f-t">
    <div class="collapse" id="navbarToggleExternalContent">
      <div class="bg-dark p-4">
        <a href="/admin/pasien-per-kelurahan"><h4 class="text-white">Kelola Data</h4></a>
      </div>
    </div>
    <nav class="navbar navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="nav navbar-nav navbar-right">
        <h4 style="color: cornsilk">Peta Sebaran COVID-19 Provinsi Bali</h4>
      </ul>
    </nav>
  </div>

  <div class="container-fluid">
  

    <section class="section" style="margin-top:25px"></section>
    <div class="row">
        <div class="col-sm-3">
          <div class="row ml-2">
            <div class="card border-primary" style="width: 21rem">
              <div class="card-header">Pilih Berdasarkan</div>
              <div class="card-body text-primary">
                <form action="{{ route('peta-search') }}" method="get">
                  @csrf
                  <div class="row">
                    <div class="col-9">
                      <div class="form-group">
                        <label class="col-sm control-label">Tanggal</label>
                        <div class="col-sm">
                            <div class="input-group input-group-sm">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" class="form-control" id="tanggal" placeholder="Tanggal" name="tanggal"  max=
                                <?php
                                    echo date('Y-m-d');
                                ?>>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col mt-4">
                      <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#create">Cari</button>
                    </div>
                  </div>
                 </form>
              </div>
            </div>
          </div>
          <div class="row ml-2 mt-4 mb-4">
            <div class="card" style="width: 21rem;">
              <div class="card-header">
                Total Positif
              </div>
              <ul class="list-group list-group-flush">
              <h5 class="list-group-item">{{ $total }} Orang</h5>
              </ul>
            </div>
          </div>

        </div>

        <div class="col">
            <div class="card border-primary mb-3" >
                <div class="card-header">Peta Sebaran - {{ $tanggal }}</div>
                <div class="card-body">
                    <div id="map" style="height: 580px; width: 100%; position: relative;"></div>  
                </div>
            </div>
        </div>
    </div>
    
    <canvas id="myChart" style="display: fixed;
    height: 220px;
    width: 500px;"></canvas>
    
</div>

    {{-- <div id="map"></div> --}}

    <script>
        var map = L.map('map', {fullscreenContril:true});
        map.setView(new L.LatLng(-8.6540914, 115.1767269), 9);

        var mymap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a> | Made Hari Kesuma Arta-170551066',
            maxZoom: 30,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            opacity: 0.90,
            accessToken: 'pk.eyJ1IjoiaGFyaWtlc3VtYSIsImEiOiJjazZxMmNibnExcWluM2RvNzhndGx0YjRzIn0.cEyjePogDTkNTbiMnugU6Q'
        }).addTo(map);
      
        var dataKelurahans = {!! json_encode($dataKelurahans) !!}

 
        console.log(dataKelurahans)

        // Instantiate KMZ parser (async)
        var kmzParser = new L.KMZParser({
          onKMZLoaded: function(layer, name) {
            control.addOverlay(layer, name);
            var layers = layer.getLayers()[0].getLayers();

            layers.forEach(function(layer,index){
                var kabupaten  = layer.feature.properties.NAME_2;
                var kecamatan  = layer.feature.properties.NAME_3;
                var kelurahan  = layer.feature.properties.NAME_4;
                kelurahan = kelurahan.replace(/\s+/g, " ");
                // console.log(kelurahan)
                if(dataKelurahans[kelurahan] !== undefined){
                  layer.setStyle({fillOpacity:'0.9',fillColor:dataKelurahans[kelurahan][0].color,color:'#5F5456',weight:1,opacity:1});
                }
                else{

                }

                // console.log(lokasi)
                layer.bindPopup('<table class="table table-striped table-dark">'+
                            '<tbody>'+
                              '<thead>'+
                                '<tr>'+
                                  '<th scope="col">Kelurahan</th>'+
                                  '<th scope="col">'+ dataKelurahans[kelurahan][0].kelurahan +'</th>'+
                                '</tr>'+
                              '</thead>'+
                              '<tr>'+
                                '<td>Level</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].level+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>PPLN</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].ppln+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>PPDN</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].ppdn+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>TL</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].tl+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>Lainya</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].lainya+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>Perawatan</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].perawatan+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>Sembuh</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].sembuh+ "<b>" +'</td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>Meninggal</td>'+
                                '<td>'+ "<b>"+ dataKelurahans[kelurahan][0].meninggal+ "<b>" +'</td>'+
                              '</tr>'+
                              '</table>')
        
            })
            layer.addTo(map);
            // console.log(layers)
            
          }
        });
        // Add remote KMZ files as layers (NB if they are 3rd-party servers, they MUST have CORS enabled)
        kmzParser.load('/kmz/bali-kelurahan.kmz');
      
        var control = L.control.layers(null, null, { collapsed:false }).addTo(map);
      </script>

<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: {!! json_encode($date7) !!},
          datasets: [{
              label: '# Positif',
              data: {!! json_encode($total7) !!},
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 2
          }]
      },
      options: {
        responsive: true,
        layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
      }
  });
  </script>
</body>
</html>
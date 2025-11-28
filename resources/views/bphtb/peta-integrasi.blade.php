@extends('layouts/contentNavbarLayout')

@section('title', 'Peta Integrasi - Bidang Tanah')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">PETA INTEGRASI</h5>
                <small class="text-muted">Klik pada bidang untuk melihat informasi</small>
            </div>
            <div class="card-body">
                <div id="map" style="height: 500px; margin-bottom: 1rem;"></div>
                <div id="feature-info" class="alert alert-info" role="alert">
                    Klik pada bidang di peta untuk melihat detail.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([-6.8928, 109.6996], 14);

    // Basemap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // WMS Layer
    const wmsLayer = L.tileLayer.wms('http://192.168.75.15/geoserver/bpn/wms', {
      layers: 'bpn:Join_gamer',
      format: 'image/png',
      transparent: true,
      version: '1.1.0',
      crs: L.CRS.EPSG4326
    }).addTo(map);

    // Fungsi GetFeatureInfo
function onMapClick(e) {
  const latlng = e.latlng;
  const point = map.project(latlng);
  const half = 50;

  // Hitung titik NW dan SE
  const nw = map.unproject([point.x - half, point.y - half]);
  const se = map.unproject([point.x + half, point.y + half]);

  // Pastikan BBOX valid: minLat < maxLat
  const minLat = Math.min(nw.lat, se.lat);
  const maxLat = Math.max(nw.lat, se.lat);
  const minLng = Math.min(nw.lng, se.lng);
  const maxLng = Math.max(nw.lng, se.lng);

  const bbox = `${minLng},${minLat},${maxLng},${maxLat}`;

  const params = new URLSearchParams({
    SERVICE: 'WMS',
    VERSION: '1.1.1',
    REQUEST: 'GetFeatureInfo',
    LAYERS: 'bpn:Join_gamer',
    QUERY_LAYERS: 'bpn:Join_gamer',
    SRS: 'EPSG:4326',
    BBOX: bbox,
    WIDTH: '101',
    HEIGHT: '101',
    X: '50',
    Y: '50',
    INFO_FORMAT: 'text/html',
    FEATURE_COUNT: '50'
  });

  // Gunakan proxy Laravel jika masih ada CORS
  const url = `/proxy/geoserver?${params.toString()}`;

  fetch(url)
    .then(r => r.text())
    .then(html => {
      const info = document.getElementById('feature-info');
      if (html.includes('<table class="featureInfo">')) {
        const style = html.match(/<style[^>]*>([\s\S]*?)<\/style>/i)?.[0] || '';
        const table = html.match(/<table class="featureInfo">[\s\S]*?<\/table>/i)?.[0] || '';
        info.innerHTML = `<div style="overflow:auto; max-height:400px;">${style}${table}</div>`;
      } else {
        info.innerHTML = '<div class="alert alert-warning">Tidak ada data bidang di lokasi ini.</div>';
      }
    })
    .catch(e => {
      console.error(e);
      document.getElementById('feature-info').innerHTML = '<div class="alert alert-danger">Gagal memuat informasi bidang.</div>';
    });
}

    map.on('click', onMapClick);
  });
</script>
@endsection
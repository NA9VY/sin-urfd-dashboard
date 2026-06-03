<?php
// Lightning Map - Clean version for URFD Dashboard
?>

<div style="padding: 12px 15px; background:#1e1e1e; border-bottom: 2px solid #ffcc00;">
    <h2 style="margin:0; color:#ffcc00;">⚡ Southern Indiana Lightning Map</h2>
</div>

<div id="lightningmap" style="height: calc(100vh - 125px); width: 100%; background:#0f0f0f;"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    window.addEventListener('load', function() {
        const map = L.map('lightningmap', {
            zoomControl: true
        }).setView([38.97, -86.13], 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let markers = [];

        function loadLightningStrikes() {
            fetch('lightning_map_get.php')
                .then(r => r.json())
                .then(strikes => {
                    markers.forEach(m => map.removeLayer(m));
                    markers = [];

                    strikes.forEach(strike => {
                        const ageMinutes = (Date.now() / 1000 - strike.timestamp) / 60;
                        if (ageMinutes > 40) return;

                        const marker = L.circleMarker([strike.lat, strike.lon], {
                            radius: 10,
                            fillColor: "#ffcc00",
                            color: "#ff0000",
                            weight: 4,
                            opacity: 0.95,
                            fillOpacity: 0.85
                        }).addTo(map);

                        marker.bindPopup(`<b>⚡ Lightning Strike</b><br>Distance: ${strike.distance} mi<br>${ageMinutes.toFixed(1)} min ago`);
                        markers.push(marker);
                    });
                })
                .catch(err => console.error(err));
        }

        setInterval(loadLightningStrikes, 5000);
        loadLightningStrikes();
    });
</script>

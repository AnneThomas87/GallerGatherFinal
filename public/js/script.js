if(document.querySelector("#map"))
{
    const map = L.map('map').setView([43.2965, 5.3698], 5);
    
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    
    let popup = L.popup();
    
    function onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("tu a cliquer sur" + e.latlng.toString())
            .openOn(map);
    }
    
    map.on('click', onMapClick);
    
    var marker = L.marker([43.2965, 5.3698]).addTo(map);
    marker.bindPopup("<b>Vernissage</b><br>Artiste marseillais").openPopup();


    fetch("api/events")
    .then(res => res.json())
    .then(data => {
        data.events.forEach(evenement => {
            if(evenement.hasOwnProperty('lat'))
            {
                const marker = L.marker([evenement.lat, evenement.lon ]).addTo(map)
                marker.bindPopup(evenement.name).openPopup();
    
            }
        });
        });
}







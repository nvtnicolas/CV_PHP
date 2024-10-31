function initMap() {
    const cityLocation = { lat: 43.6103201, lng: 1.4310661 };
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: cityLocation
    });
    const marker = new google.maps.Marker({
        position: cityLocation,
        map: map
    });
}
window.onload = initMap;
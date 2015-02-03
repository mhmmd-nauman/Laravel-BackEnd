//Google Map
$(function() {
    if ($('#js-location').length) {
        var lat = $('.js-location-lat').val(),
            lng = $('.js-location-lng').val();

        function initialize() {
            var myLatlng = new google.maps.LatLng(lat, lng);
            var mapOptions = {
                zoom: 15,
                center: myLatlng
            }
            var map = new google.maps.Map(document.getElementById('js-location'), mapOptions);

            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                draggable: true
            });

            google.maps.event.addListener(marker, 'dragend', function(event) { // save cooordinates after move marker
                $('.js-location-lat').val(event.latLng.lat());
                $('.js-location-lng').val(event.latLng.lng());
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    }
});
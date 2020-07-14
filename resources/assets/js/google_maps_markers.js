/**
 * Created by Nguyen Tuan Linh on 2016-12-13.
 */
function createGoogleMapsMarker($map, $options) {
    if ($map.length <= 0) return null;
    return new GoogleMapsMarkers($map, $options);
}
function GoogleMapsMarkers($map, options) {
    if ($map.length <= 0) return;

    if (typeof options === 'undefined') options = {};

    this.$map = $map;
    this.defaultCenter = options.center ? options.center : {lat: 21.0278, lng: 105.8342};
    this.defaultCenterName = options.centerName ? options.centerName : 'Hanoi, Vietnam';
    this.zoom = options.zoom ? options.zoom : 8;

    this.geoCoder = new google.maps.Geocoder;
    this.infoWindow = new google.maps.InfoWindow;
    this.map = null;
    this.map = new google.maps.Map(this.$map.get(0), {
        center: this.defaultCenter,
        zoom: this.zoom
    });
    this.currentMarker = null;
    this.initMarkCenter = options.markCenter ? options.markCenter == true : false;
    this.reset();
}
GoogleMapsMarkers.prototype.reset = function () {
    this.center = this.defaultCenter;
    this.centerName = this.defaultCenterName;
    if (this.initMarkCenter) {
        this.showMarker();
    }
    else {
        if (this.currentMarker) this.currentMarker.setMap(null);
    }
};
GoogleMapsMarkers.prototype.showMarker = function () {
    if (this.currentMarker) this.currentMarker.setMap(null);
    this.map.setCenter(this.center);
    this.currentMarker = new google.maps.Marker({
        map: this.map,
        position: this.center
    });
    this.infoWindow.setContent(this.centerName);
    this.infoWindow.open(this.map, this.currentMarker);

    var self = this;
    self.currentMarker.addListener('click', function () {
        self.infoWindow.setContent(self.centerName);
        self.infoWindow.open(self.map, self.currentMarker);
    });
};
GoogleMapsMarkers.prototype.enableClickToAddress = function (successCallback, errorCallback) {
    var self = this;
    self.clickResult = {};
    self.map.addListener('click', function (e) {
        self.clickResult.latLng = e.latLng;

        if (e.placeId) {
            self.clickResult.placeId = e.placeId;
            self.geocodePlaceId(successCallback, errorCallback);
        }
        else {
            self.geocodeLatLng(successCallback, errorCallback);
        }
    });
};
GoogleMapsMarkers.prototype.geocodePlaceId = function (successCallback, errorCallback) {
    var self = this;
    self.geoCoder.geocode({'placeId': self.clickResult.placeId}, function (results, status) {
        if (status === 'OK') {
            if (results[0]) {
                self.geocodeResult(results[0].geometry.location, results[0].formatted_address);
                successCallback.call(self);
                return;
            }
        }

        errorCallback.call(self, status, results);
    });
};
GoogleMapsMarkers.prototype.geocodeLatLng = function (successCallback, errorCallback) {
    var self = this;
    self.geoCoder.geocode({'location': self.clickResult.latLng}, function (results, status) {
        if (status === 'OK') {
            if (results[1]) {
                self.geocodeResult(self.clickResult.latLng, results[1].formatted_address);
                successCallback.call(self);
                return;
            }
        }

        errorCallback.call(self, status, results);
    });
};
GoogleMapsMarkers.prototype.geocodeResult = function (location, address) {
    this.clickResult.address = address;
    this.center = location;
    this.centerName = address;
    this.showMarker();
};
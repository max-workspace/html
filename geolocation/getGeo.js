var GeoObj = {
    // 获取地理位置的配置参数
    'timeout' : 1000,
    'maximumAge' : 60000,
    'enableHighAccuracy' : false,

    // 判断是否支持地理位置
    'judgeGeolocationEnable' : function (){
        return 'geolocation' in navigator;
    },

    // 获取地理位置
    'getGeolocation' : function(){
        navigator.geolocation.getCurrentPosition(function(pos){
            console.log(pos);
            var crd = pos.coords;
            console.log('Your current position is:');
            console.log('Latitude : ' + crd.latitude);
            console.log('Longitude: ' + crd.longitude);
            console.log('More or less ' + crd.accuracy + ' meters.');
        }, function(error){
            console.log('ERROR(' + error.code + '): ' + error.message);
        }, {
            maximumAge: 0,
            timeout: 10000,
            enableHighAccuracy:true,
        });
    },

    // 监听地理位置
    'watchPosition' : function(){
        navigator.geolocation.watchPosition(function (pos) {
            var crd = pos.coords;
            console.log(crd.latitude, crd.longitude);
        }, function (error) {
            console.log('ERROR(' + error.code + '): ' + error.message);
        }, {
            enableHighAccuracy: false,
            timeout: 5000,
            maximumAge: 0
        });
    },
};
GeoObj.getGeolocation();
GeoObj.watchPosition();

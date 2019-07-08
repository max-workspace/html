<!DOCTYPE html>
<html>
<head>
    <title>首页</title>
    <meta charset="utf-8" />
    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.15&key=bb216e7303d610be739de6dad045569b"></script>
</head>
<body>
<div>show geo</div>
<div id="lng">
</div>
<div id="lat">
</div>
<div id="error_message"></div>
<div id="container"></div>
</body>
<script type="application/javascript">
    var map = new AMap.Map('container', {
        resizeEnable: true
    });
    map.plugin('AMap.Geolocation', function() {
        var geolocation = new AMap.Geolocation({
            // 是否使用高精度定位，默认：true
            enableHighAccuracy: true,
            // 设置定位超时时间，默认：无穷大
            timeout: 10000,
            // 定位按钮的停靠位置的偏移量，默认：Pixel(10, 20)
            buttonOffset: new AMap.Pixel(10, 20),
            //  定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            zoomToAccuracy: true,
            //  定位按钮的排放位置,  RB表示右下
            buttonPosition: 'RB'
        });

        geolocation.getCurrentPosition()
        AMap.event.addListener(geolocation, 'complete', onComplete)
        AMap.event.addListener(geolocation, 'error', onError)

        function onComplete (data) {
            // data是具体的定位信息
            document.getElementById("lng").innerHTML  =  data.position.lng;
            document.getElementById("lat").innerHTML  =  data.position.lat;
            console.log(data);
        }

        function onError (data) {
            // 定位出错
            console.log(data);
        }
    })
</script>
</html>
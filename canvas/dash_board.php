<!DOCTYPE html>
<html>
    <head>
        <title>仪表盘</title>
        <!--动态设置像素比-->
        <meta charset="utf-8" />
        <script>
            var iScale = 1;
            iScale = iScale / window.devicePixelRatio;
            document.write('<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=' + iScale + ',minimum-scale=' + iScale + ',maximum-scale=' + iScale + '">')
        </script>
        <!--动态设置文字大小-->
        <script>
            var iWidth = document.documentElement.clientWidth;
            document.getElementsByTagName('html')[0].style.fontSize = iWidth / 16 + 'px';
        </script>
        <link rel="stylesheet" type="text/css" href="../../css/common.css">
        <style>
            #dash_board{
                margin: auto;
                width:12.8rem;
                height:12.8rem;
            }
            #dash_board_canvas{
                width:12.8rem;
                height:12.8rem;
                border:0px solid rgba(236,238,245,1);
            }
            #weather_img{
                display: none;
                width:2.2rem;
                height:2.19rem;
                --top-length:3;
            }
            #temprature_text{
                display: none;
                --top-length:6;
                width:6.24rem;
                height:1.69rem;
                font-size:2.27rem;
                font-family:PangMenZhengDao;
                font-weight:bold;
                color:rgba(35,59,113,1);
                line-height:1.04rem;
            }
            #temprature_desc_text{
                display: none;
                --top-length:8;
                width:4.4rem;
                height:0.57rem;
                font-size:0.6rem;
                font-family:PingFang-SC-Bold;
                font-weight:bold;
                color:rgba(171,179,197,1);
                line-height:2.1rem;
            }
            #dial_scale{
                display: none;
            }
            #dial_scale_start_point{
                display: none;
                --top-length:0.01;
                width:0.65rem;
                height:0.36rem;
                background:rgba(221,225,232,1);
                border-radius:0rem;
            }
        </style>
    </head>
    <body>
        <img id="weather_img" src="../../images/clear_day.png" alt="天气图片">
        <img id="dial_scale" src="../../images/dial_scale.png" alt="栅格刻度盘">
        <img id="dial_scale_start_point" src="../../images/start_point.png" alt="栅格刻度盘起始点">
        <div id="temprature_text">26.3°</div>
        <div id="temprature_desc_text">晴 降水率:40%</div>
        <div id="dash_board">
            <canvas id="dash_board_canvas">
                您的浏览器不支持canvas标签。
            </canvas>
            <canvas id="dial_scale_start_point_canvas">
                您的浏览器不支持canvas标签。
            </canvas>
        </div>
    </body>
    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
    <script type="application/javascript">
        $(function(){
            // 定义rem和px的转换值
            var rem2px = 70.375;

            // 定义外侧圆的信息
            var cir_r = $("#dash_board_canvas").width()/2;
            var line_w = 0.2 * (window.screen.width/5);
            var circle_outer = {
                x : cir_r,                  //圆心坐标
                y : cir_r,                  //圆心坐标
                r : cir_r,                  //圆的半径
                percent : 80,               //百分比
                lineWidth : line_w,         //圆环的宽度
                color : '#93BF55',          //圆环的颜色
                fillColor : '#fff',         //填充颜色
                trailColor : '#ddd',        //轨迹颜色
                startAngle : 0.5 * Math.PI, //圆的起始角度
                endAngle : 2.5 * Math.PI,   //圆的结束角度
                clockwise : false,           //顺时针绘制
                colorStart : '#ECEEF5',
                colorEnd : '#FFCB2B',
            };

            var circle_inner = {
                x : cir_r,                  //圆心坐标
                y : cir_r,                  //圆心坐标
                r : cir_r * 11 / 13,            //圆的半径
                percent : 40,               //百分比
                lineWidth : line_w * 4.5,         //圆环的宽度
                color : '#93BF55',          //圆环的颜色
                fillColor : '#fff',         //填充颜色
                trailColor : '#ddd',        //轨迹颜色
                startAngle : 0.5 * Math.PI, //圆的起始角度
                endAngle : 2.5 * Math.PI,   //圆的结束角度
                clockwise : false,          //顺时针绘制
                colorStart : '#ECEEF5',
                colorEnd : '#6386DE',
            };

            drawDashBoard('dash_board_canvas', circle_outer, circle_inner, rem2px);

            drawDialScaleStartPoint('dial_scale_start_point_canvas', circle_outer, rem2px);

            // 绘制仪表图
            function drawDashBoard(ele_id, circle_outer, circle_inner, rem2px){
                // 设置画布属性
                var canvas = document.getElementById(ele_id);
                canvas.width = canvas.height = circle_outer.r * 2;
                canvas.style.borderRadius="50%";

                if(canvas.getContext){
                    var ctx = canvas.getContext("2d");
                    // 绘制两个圆环
                    drawCircle(ctx, circle_outer);
                    drawCircle(ctx, circle_inner);

                    // 绘制栅格图
                    var img=document.getElementById('dial_scale');
                    var img_width = circle_inner.r * 2 + circle_inner.lineWidth;
                    var img_ = circle_outer.r - circle_inner.r - circle_inner.lineWidth * 0.5;
                    ctx.drawImage(img, img_, img_, img_width, img_width);

                    // 绘制天气图片
                    drawImage(ctx, 'weather_img', circle_outer, rem2px);

                    // 填充温度数值
                    drawFont(ctx, 'temprature_text', circle_outer, rem2px);

                    // 绘制气温简介
                    drawFont(ctx, 'temprature_desc_text', circle_outer, rem2px);
                }
            }

            // 绘制圆环
            function drawCircle(ctx, circle){
                drawBackgroundCircle(ctx, circle);
                drawMove(ctx, circle);
            }

            // 绘制底层的背景圆环
            function drawBackgroundCircle(ctx, circle){
                ctx.beginPath();
                ctx.strokeStyle = circle.trailColor;
                ctx.lineWidth = circle.lineWidth;
                ctx.arc(circle.x, circle.y, circle.r, circle.startAngle, circle.endAngle, circle.clockwise);
                ctx.stroke();
            }

            // 绘制按百分比显示的圆环
            function drawMove(ctx, circle){
                ctx.beginPath();
                // 计算以6点钟方位为起点的结束角度
                var endAngle = (circle.percent/100) * 2 * Math.PI + 0.5 * Math.PI;

                //创建渐变对象
                var g = ctx.createLinearGradient(circle.r, circle.r * 2, circle.r, 0);
                g.addColorStop(0, circle.colorStart); //添加颜色点
                g.addColorStop(1, circle.colorEnd); //添加颜色点
                ctx.strokeStyle = g;     //使用渐变对象作为圆环的颜色

                ctx.lineWidth = circle.lineWidth;
                ctx.arc(circle.x, circle.y, circle.r, circle.startAngle, endAngle, circle.clockwise);
                ctx.stroke();
            }

            // 绘制图片
            function drawImage(ctx, ele_id, circle, rem2px){
                var ele_obj = $('#' + ele_id);
                var img_width = ele_obj.width();
                var img_height = ele_obj.height();
                var img_x = circle.x - img_width / 2;
                var img_y = ele_obj.css('--top-length');
                img_y = img_y * rem2px;
                var img=document.getElementById(ele_id);
                ctx.drawImage(img, img_x, img_y, img_width, img_height);
            }

            // 绘制文字
            function drawFont(ctx, ele_id, circle, rem2px){
                var ele_obj = $('#' + ele_id);
                var text_font_size = ele_obj.css('font-size');
                var text_font_family = ele_obj.css('font-family');
                ctx.font = text_font_size + ' ' + text_font_family;

                var text_width = ele_obj.width();
                var text_height = ele_obj.height();
                var text_x = circle.x;
                var text_y = ele_obj.css('--top-length');
                text_y = text_y * rem2px + text_height / 2;
                var text_content = ele_obj.html();
                var text_color = ele_obj.css('color');
                ctx.fillStyle = text_color;
                ctx.textAlign="center";
                ctx.fillText(text_content, text_x, text_y);
            }

            // 绘制刻度盘起始点
            function drawDialScaleStartPoint(ele_id, circle, rem2px){
                var canvas = document.getElementById(ele_id);
                canvas.width = canvas.height = circle.r * 2;

                if(canvas.getContext){
                    var ctx = canvas.getContext("2d");
                    drawImage(ctx, 'dial_scale_start_point', circle, rem2px);
                }
            }
        });
    </script>
</html>
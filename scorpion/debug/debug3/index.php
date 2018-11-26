<?php
 
 echo '11111';


const EARTH_RADIUS = 6371.0;
 /**
  * @param [type] $theta
  * @return void
  */
 function HaverSin($theta)
 {  
    $v =sin($theta/2);
    return $v * $v;
 }


function ConvertDegreesToRadians($degrees)
 {
    return $degrees * pi() / 180;
 }


 function Distance($lat1, $lon1, $lat2, $lon2)
 {
     //用haversine公式计算球面两点间的距离。
     //经纬度转换成弧度
     $lat1 = ConvertDegreesToRadians($lat1);
     $lon1 = ConvertDegreesToRadians($lon1);
     $lat2 = ConvertDegreesToRadians($lat2);
     $lon2 = ConvertDegreesToRadians($lon2);
     //差值
     $vLon = abs($lon1 - $lon2);
     $vLat = abs($lat1 - $lat2);
     //h is the great circle distance in radians, great circle就是一个球体上的切面，它的圆心即是球心的一个周长最大的圆。
     $h = HaverSin($vLat) + cos($lat1) * cos($lat2) * HaverSin($vLon);
     $distance = 2 * EARTH_RADIUS * asin(sqrt($h));
     return $distance;
 }


 var_dump(Distance(37.742044, 112.652795, 37.736943, 112.652507));
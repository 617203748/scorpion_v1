<?php
require 'HttpsRequest.php';
require 'CodeImg.php';
$CodeImg = new CodeImg;
$CodeImg->setId(113);//二维码场景ID
$CodeImg->setAccessToken('XCnu1v-aJP8L8RGEYVdwQ4uXGXrAs053Af2jI0t6U4GW-RDq26MwhFBkw3NpBoIWOG3p_h9_sg2MIKKL6m5P8eM3Nc1h_ygT5rqqun2sebYje1L_Me26KC3jLxwtwKq_WIYcAFASEU');
echo $CodeImg->getTicket(), '<br/>', $CodeImg->getCodeUrl();//根据自身业务需要取出ticket或url
<?php
$uuid = $_GET["uuid"];
http_response_code(200);
header("Content-Type: image/webp");
if(strpos($uuid,"00000000-0000-0000-0009-") === false){
    $suuid = explode("-",$uuid);
    $xuid = hexdec($suuid[3].$suuid[4]);
    $json = json_decode(file_get_contents("https://api.geysermc.org/v2/skin/".$xuid),true);
    $json = json_decode(base64_decode($json["value"]),true);
    $skinurl = $json["textures"]["SKIN"]["url"];
}else{
    $json = json_decode(file_get_contents("https://sessionserver.mojang.com/session/minecraft/profile/".$uuid),true);
    $json = json_decode(base64_decode($json["properties"][0]["value"]),true);
    $skinurl = $json["textures"]["SKIN"]["url"];
}
$size = 64; //sizeの数値は8で割り切れるものにしてください。表示がバグる気がします。
$base = imagecreatefromstring(file_get_contents($skinurl));
$resimage = imagecreatetruecolor($size,$size);
$image = imagecreatetruecolor(8,8);
imagecopyresized($image, $base, 0, 0, 8, 8, 8, 8, 8, 8);
imagecolortransparent($base, imagecolorat($base, 63, 0));              
imagecopyresized($image, $base, 0, 0, 40, 8, 8, 8, 8, 8);
$b = $size/8;
foreach(range(0,7) as $x){
    foreach(range(0,7) as $y){
        $color = imagecolorat($image,$x,$y);
        imagefilledrectangle($resimage, $x*$b, $y*$b, $x*$b+$b, $y*$b+$b, $color);
    }
}
imagewebp($resimage);
imagedestroy($image);
imagedestroy($resimage);
imagedestroy($base);
?>

<?php
$uuid = $_GET["uuid"]; //GETの値からuuidを取得します
http_response_code(200); //200(OK)のステータスコードを送信します
header("Content-Type: image/webp"); //ページが画像ファイルであることを報告します
if(strpos($uuid,"00000000-0000-0000-0009-") === false){ //統合版かどうかを確認します
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
$b = $size/8; //マイクラのスキンの顔部分は8pxなので、8で割ります
foreach(range(0,7) as $x){
    foreach(range(0,7) as $y){
        $color = imagecolorat($image,$x,$y);
        imagefilledrectangle($resimage, $x*$b, $y*$b, $x*$b+$b, $y*$b+$b, $color);
    }
}
imagewebp($resimage); //出力します
imagedestroy($image); //メモリ破棄
imagedestroy($resimage); //メモリ破棄
imagedestroy($base); //メモリ破棄
?>

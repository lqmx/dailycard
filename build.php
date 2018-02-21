<?php

$v = time();

$htmlMain = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Card</title>
    <link rel="stylesheet" href="build.css?v$v">
</head>
<body>
<div class="table">
%s
</div>
<script src="build.js?v$v"></script>
</body>
</html>
HTML;



$htmlCard = <<<CARD
<div class="card" data-id="%s">
        <div class="circle"></div>
        <div class="main">
            <img src="../data/%s" alt="" draggable="false">
        </div>
        <div class="footer">
            <div class="content %s">%s</div>
            <div class="date">%s</div>
        </div>
    </div>
CARD;


$filePath = "dailycard.md";


$content = file_get_contents($filePath);
$dailyCard = array_filter(explode("\n", $content));
unset($dailyCard[0]);


$html = "";
//echo count($dailyCard), PHP_EOL;
if(count($dailyCard)%3==0) {
    $dailyCard = array_chunk ($dailyCard, 3);
    $dailyCard = array_reverse($dailyCard);
    foreach ($dailyCard as $k => $v) {
        $date = str_replace("> ", "", $v[0]);
        $txt = str_replace("> ", "", $v[1]);

        $fontSize = "font-l";
        $strLen = strlen($txt);
        if($strLen > 30 and $strLen < 60) {
            $fontSize = "font-m";
        } elseif($strLen > 60) {
            $fontSize = "font-s";
        }
//        echo $txt , ' ', strlen($txt), ' ', $fontSize, PHP_EOL;
        $img = str_replace("> ", "", $v[2]);
        $html .= sprintf ($htmlCard, $k, $img, $fontSize, $txt, $date);
    }
}

$html = sprintf($htmlMain, $html);

file_put_contents("build/index.html", $html);


// build css
$css = array(
    "css/normalize.css",
    "css/style.css",
);

$cssContent = "/* build time " . date("Y-m-d h:i:s", intval($v)) ." */". PHP_EOL;
foreach ($css as $v) {
    $cssContent .= PHP_EOL . PHP_EOL . "/* build $v */" . PHP_EOL. PHP_EOL;
    $cssContent .= file_get_contents($v);
}
file_put_contents("build/build.css", $cssContent);

// build js
$js = array(
    "dep/jquery.js",
    "js/Drag.js",
    "js/index.js",
);
$jsContent = "/* build time " . date("Y-m-d h:i:s", intval($v)) ." */". PHP_EOL;
foreach($js as $v) {
    $jsContent .= PHP_EOL . PHP_EOL . "/* build $v */". PHP_EOL . PHP_EOL;
    $jsContent .= file_get_contents($v);
}
file_put_contents("build/build.js", $jsContent);

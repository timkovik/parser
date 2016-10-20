<?php 
header("Cache-Control: no-store, no-cache, must-revalidate");
header('Content-Type: text/html; charset=utf8');
function remote_get($url, $callback = null) {
    $uagent = 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:16.0) Gecko/20100101 Firefox/16.0';
    $ch = curl_init ();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    $output = curl_exec ($ch);
    if (isset($callback)) {
        return $callback($output);
    } else {
        return $output;
    }
}
function only($html) {
    
    $html=iconv('windows-1251', 'utf-8', $html);    
    $html = explode("<body", $html);
    $html = $html[1];
    $html = explode("</body>", $html);
    $html = $html[0];
    $html = explode('details-full', $html);
    $html = $html[1];
    $html = str_replace("script", "scrpt", $html);
    $html = str_replace("style", "stl", $html);
    $html = str_replace("\n", "", $html);

    $props = array();
    preg_match_all('|<div class="prop(.*)</div>|Ui',$html,$out); // prop
    preg_match_all('|<div class="page-title">(.*)</div>|Ui',$html,$itemName); // название
    preg_match_all('|<div class="price"><span class="value">(.*)</span>(.*)</div>|Ui',$html,$price); // цена
    $props['itemName'] = trim($itemName[1][0]);
    $props['price'] = trim($price[1][1]);

    foreach ($out[0] as $str){
        preg_match_all('|<span class="name">(.*)</span>|Ui',$str,$outName); // prop/name
        preg_match_all('|<span class="value">(.*)</span>|Ui',$str,$outValue); // prop/value
        $next = count($props);
        $name = $outName[1][0];
        $value = $outValue[1][0];
        $props[$name] = $value;
        // $props[$next]['name'] = $outName[1][0];
        // $props[$next]['value'] = $outValue[1][0];
    }

    $json = json_encode($props, JSON_UNESCAPED_UNICODE);
    return $json;

}
///////////////////////////////////////////////////////////////////////////
//////////////Функции объявлены////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
$result = remote_get($_POST['url'], 'only');
echo trim($result);
























?>
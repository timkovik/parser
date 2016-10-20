<?php 
header("Cache-Control: no-store, no-cache, must-revalidate");
header('Content-Type: text/html; charset=windows-1251');

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
    if (isset($callback)){
        return $callback($output, $url);
    }else{
        return $output;
    }
}
function only($html, $url) {
    $html = explode("<body", $html);
    $html = $html[1];
    $html = explode("</body>", $html);
    $html = $html[0];
    $html = str_replace("script", "scrpt", $html);
    $html = str_replace("style", "stl", $html);
    $html = str_replace("\n", "", $html);
    $links = array();

    preg_match_all('|<td class="goods">(.*?)<\/td>|s',$html,$out);
    $a = array();
    echo "<sctipt>console.log(".$out.");</script>";

    foreach ($out[0] as $str){
        preg_match_all('|<a href="(.*)>(.*)</a>|Ui',$str,$out_str); // названия
        $links[] = $out_str[1][0];
        for ($i=0; $i < $links; $i+2) { 
            unset($links[$i]);
        }
        echo $links;
    }
    foreach ($links as $key => $link){
        $links[$key] = '"'.$link;
    }


    $json = '{"links":[';
    $json .= implode(',', $links);
    $json .= ']}';    
    print_r($json);
}
///////////////////////////////////////////////////////////////////////////
//////////////Функции объявлены////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
$url = $_POST['link'];
$result = remote_get($url,'only');
echo $result;























?>
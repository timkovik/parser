

<?php
echo '<a class="tab" href="#armaturaa1">Арматура А1</a>
        <a class="tab" href="#armaturaa1">Арматура А3</a>
        <a class="tab" href="#katanka">Катанка</a>
        <a class="tab" href="#shveler">Швелер</a>
        <a class="tab" href="#balka">Балка</a>
        <a class="tab" href="#ugolok_ravn">Уголок равнополочный</a>
        <a class="tab" href="#ugolok_neravn">Уголок неравнополочный</a>
        <a class="tab" href="#kvadrat">Квадрат</a>
        <a class="tab" href="#polosa">Полоса</a>
        <a class="tab" href="#setka">Сетка</a>
        <a class="tab" href="#truba_es">Труба Электросварная</a>
        <a class="tab" href="#truba_vgp">Труба ВГП</a>
        <a class="tab" href="#truba_vgp_oz">Труба ВГП оц</a>
        <a class="tab" href="#provoloka">Проволока</a>
        <a class="tab" href="#listhk">Лист х\к</a>
        <a class="tab" href="#listgk">Лист г\к</a>
        <a class="tab" href="#list_ocinkovanniy">Лист оцинкованные</a>
        <a class="tab" href="#list-tolst">Лист толстый</a>
										        <a class="tab" href="#truba_krug">Труба круглая</a>
        <a class="tab" href="#krug">Круг стальной</a>
        <a class="tab" href="#truba_prof">Труба профильная</a>
        <a class="tab" href="#proflist_oz">Профлист Оц.</a>
        <a class="tab" href="#truba_bessh">Труба бесшовная</a>
        <a class="tab" href="#truba_electrosvar">Труба электросварная</a>
        <a class="tab" href="#listr">Лист Рифленый</a>




        ';
echo "<div id='armaturaa1'>";
echo '<b>Арматура А1</b>';

echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/armatura_A1' );
$json = file_get_contents(__dir__.'/nacenka.json');
 // $json = json_encode($json);
 // var_dump($json);

$nacenka = json_decode($json);
// var_dump($nacenka);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);

// echo '<pre>';print_r($out);echo '</pre>';
// 
$out=$out[0];
for ($i=1; $i < 47; $i=$i+2) { 
            unset($out[$i]);
        }
unset($out[47]);
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);

}
// var_dump($out);
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);
$out1 = $out1[0];
// var_dump($out1);
// 	echo '<br>';
// 	echo '<br>';
$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	
		// var_dump($out1[$i]);
	preg_match_all('/\s{15,}(.*?)\s{15}/s',$out1[$i],$out2);
	$ceni[$i] = $out2[0][10];
		// var_dump($ceni[$i]);
    $ceni[$i] = trim($ceni[$i]);

    // $ceni[$i] = str_replace("&nbsp;", "", $ceni[$i]);
    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));
	unset($ceni[0]);
	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = iconv('windows-1251', 'UTF-8',$ceni[$i]);
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}
		// var_dump($ceni);
		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
		// var_dump($price);
	}/////////Прайс арматуры/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'armatura_a1', null, null, 2));
$objects = $objects['lines']['nodes:item'];
// var_dump($objects);
$base = json_decode(file_get_contents(__dir__.'/base.json'), true);

$n = 0;

echo "<form method='post'>";
foreach ($objects as $key=>$value){

	$page = $this->getPageById($value['attribute:id']);
	$name = str_replace("D", "", $value['node:text']);
	$name = str_replace("  ", " ", $name);
	$name  = trim($name);
	if($_POST){
		$base[$name]['price'] = $_POST['price'.$n];
		$base[$name]['nacenka'] = $_POST['nacenka'.$n];
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$n];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$n."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$n."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$n."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
		
	}

	$n++;
	
}

If($_POST){echo "<h3>Сохранено</h3>";}

echo '<hr>';


?>
<?php
echo "</div><div id='armaturaa3'>";

echo "<h2>Арматура А3</h2>";
/////////////Арматура А3////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/armatura_A3' );
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/armatura_A3/PageN/2' );
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);

preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);
$out1 = $out1[0];
$price = [];


for ($i=0; $i < count($out1); $i++) { 
		$f = $out1[$i];
	preg_match('/<td class="goods">(.*?)<\/td>/s',$f,$out);
$out=$out[0];

	$out = str_replace('<td class="goods">', '', $out);

	$out = substr($out, 2);
	$out = substr($out, 0, -25);
		preg_match('/(11700)/',$f, $dlina);
	$out .= ' '.$dlina[1];

// echo $out;
	preg_match('/(А500С)/',$f, $stal);
	$out .= ' '.$stal[1];

	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);
	$ceni = $out2[0];
    $ceni = trim($ceni);

    $ceni = preg_replace("/\s/", "", $ceni);
	$ceni = preg_replace("/([&nbsp;]+)/s", "", $ceni);
	$ceni = str_replace(" ", "", $ceni);
	$minus = substr($ceni,2,2);
	$ceni = str_replace($minus, "", $ceni);
	$ceni = trim($ceni);
	$out = trim($out);

	if(empty($price[$out]) | $ceni > $price[$out]){
		$price[$out] = $ceni;
	}
	

}



	/////////Прайс арматуры a3/////////////
$base = json_decode(file_get_contents(__dir__.'/base.json'), true);

$json1 = file_get_contents(__dir__.'/nacenka_metr.json');

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'armatura_stroitelnay', null, null, 2));
$objects = $objects['lines']['nodes:item'];
$q = $n;

foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);

    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name  = trim($name);
    $name .=' 11700 А500С';
        	// echo '"'.$name.'" : "500",<br>';

   if($_POST){
		$base[$name]['price'] = $_POST['price'.$q]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$q]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$q];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$q."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$q."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$q."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$q++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo '<hr>';



// ?>

<?php
echo "</div><div id='katanka'>";

/////////////Катанка////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/katanka' );
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс катанка/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'katanka_stalnay', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$w = $q;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);

    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name  = trim($name);

      if($_POST){
		$base[$name]['price'] = $_POST['price'.$w]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$w]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$w];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$w."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$w."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$w."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$w++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo "<hr>";

// ?>
<?php
echo "</div><div id='shveler'>";

/////////////Швелер////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/shveller');
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс катанка/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'shveler', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$e = $w;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);

    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("П", "", $name);
    $name  = trim($name);

    

  if($_POST){
		$base[$name]['price'] = $_POST['price'.$e]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$e]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$e];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$e."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$e."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$e."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$e++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// var_dump($price);

// ?>
<?php
echo "</div><div id='balka'>";

/////////////Двутавр////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/balka_2t');
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);



}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс катанка/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'balka_dvutavrovay', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$r = $e;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);

    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("П", "", $name);

    $name  = trim($name);

     if($_POST){
		$base[$name]['price'] = $_POST['price'.$r]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$r]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$r];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$r."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$r."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$r."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$r++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// var_dump($price);

// ?>
<?php
echo "</div><div id='ugolok_ravn'>";

/////////////Уголок////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/ugolok_ravn');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/ugolok_ravn/PageN/2');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);

}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс уголок/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'ugolok_metallicheskiy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$t = $r;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("П", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = trim($name);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$t]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$t]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$t];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$t."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$t."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$t."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$t++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';

// ?>
<?php
echo "</div><div id='ugolok_neravn'>";

/////////////Уголок неравнополочный////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/ugolok_neravn');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);

}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс уголок/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'ugolok_metallicheskiy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$y = $t;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("П", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = trim($name);
    
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$y]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$y]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$y];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$y."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$y."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$y."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$y++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='kvadrat'>";

/////////////Квадрат////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/kvadrat_gk');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);

}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс квадрат/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'kvadrat_stalnoy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$u = $y;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("П", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// var_dump($price[$name]);

    
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$u]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$u]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$u];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$u."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$u."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$u."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$u++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='polosa'>";

/////////////Полоса////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/polosa_gk');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/polosa_gk/PageN/2');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// echo ($f)."<br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);


	$ceni[$i] = $out2[0];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);

	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);

	}


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс полоса/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'polosa_stalnaya', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$o = $u;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace("  ", " ", $name);


	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// var_dump($price[$name]);

   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$o]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$o]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$o];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$o."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$o."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$o."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$o++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='setka'>";

/////////////Сетка////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/setka_svarn/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/setka_svarn/PageN/2');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("оцинк.", "оцинк", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/м2\s*<\/td>\s\n\s*<td>\s*(.*)\s*</',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс сетка/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'setka_metallicheskay', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$p = $o;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
    $name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace("Сетка сварная неоцинкованная в картаx (сетка дорожная)", "Сетка сварная", $name);
	$razmer = $page->getValue('razmer_yachejki');
	$razmer = preg_replace('/([0-9]+х)/', '', $razmer, 1);
	$razmer = htmlspecialchars($razmer);
	if ($name == "Сетка сварная оцинкованная в картаx (сетка дорожная)"){
		$name = "Сетка сварная ".$razmer.' оцинк';
	}else if($name == "Сетка сварная из оцинкованной проволоки в рулонаx"){
		$name = 'Сетка сварная '.$razmer.' оцинк пров.';
	}else{

		$name .=' '.$razmer;
	}
	$name = str_replace("х", "x", $name);
	$name = str_replace(",", ".", $name);
	$name = str_replace("  ", " ", $name);
	// var_dump($name);
	$name = str_replace('Сетка арматурная в картаx кл. А-III', 'Сетка сварная', $name);
	$name = str_replace("  ", " ", $name);

	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// echo '"'.$name.'"<br>';
	// var_dump($price[$name]);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$p]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$p]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$p];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$p."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$p."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$p."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$p++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_es'>";

/////////////Труба электросварная////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/2');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/3');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/4');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("х/к", "", $out[$i]);
 	$out[$i] = str_replace("ы", "а", $out[$i]);
 	$out[$i] = str_replace(".", ",", $out[$i]);
  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс труба электросварная/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'trubi_stalnie_elektrosvarnie', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$a = $p;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
    $name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(",0", "", $name);
	$name = str_replace("х", "x", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price[$name]);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$a]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$a]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$a];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$a."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$a."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$a."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$a++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_vgp'>";

/////////////Труба ВПГ////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_vgp_nz');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("х/к", "", $out[$i]);
 	$out[$i] = str_replace("ы", "а", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс труба ВПГ/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'truba-vpg-opt', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$s = $a;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(",0", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$s]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$s]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$s];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$s."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$s."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$s."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$s++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_vgp_oz'>";

/////////////Труба ВПГ оцинкованная////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_vgp_oz');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("х/к", "", $out[$i]);
 	$out[$i] = str_replace("ы", "а", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс труба ВПГ оцинкованная/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'truba-vpg-opt-ocink', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$d = $s;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
    $name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(",0", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);

  if($_POST){
		$base[$name]['price'] = $_POST['price'.$d]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$d]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$d];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$d."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$d."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$d."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$d++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='provoloka'>";

/////////////Проволока////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/provoloka_arm');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/provoloka_arm');
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("х/к", "", $out[$i]);
 	$out[$i] = str_replace("ы", "а", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Вр", "ВР", $out[$i]);
  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс проволока/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'provoloka', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$f = $d;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
    $name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(",0", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$f]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$f]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$f];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$f."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$f."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$f."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$f++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='listr'>";

/////////////Лист рифленый////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listr');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("х/к", "", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Вр", "ВР", $out[$i]);
	$out[$i] = preg_replace("/(x.*)/", "", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс лист рифленый/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'list-riflenyy-tsena-chechevitsa-romb-kupit-list-gk-riflennyy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$g = $f;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
    $name = str_replace("х", "x", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(".0", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$g]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$g]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$g];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$g."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$g."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$g."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$g++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='listhk'>";

/////////////Лист Лист х\к////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listhk');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Вр", "ВР", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс лист рифленый/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'ist_xk', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$h = $g;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(".0", "", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	$name .='x1250x2500';
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$h]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$h]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$h];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$h."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$h."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$h."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$h++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='listgk'>";

/////////////Лист Лист х\к////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listgk/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listgk/PageN/2');
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Вр", "ВР", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс лист г\к/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'ist_gk', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$j = $h;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(".0", "", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	$name .='x'.trim($page->getValue('dlinna'));
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$j]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$j]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$j];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$j."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$j."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$j."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$j++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='list_ocinkovanniy'>";

/////////////Лист Лист оцинкованный////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listzn');
$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Вр", "ВР", $out[$i]);
 	$out[$i] = str_replace("x", "х", $out[$i]);


  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс лист г\к/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'list_ocinkovanniy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$k = $j;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(".0", "", $name);
	$name = str_replace("ду", "", $name);

	$name = str_replace("  ", " ", $name);
	// var_dump($name);


	$name = trim($name);
	$name .='х'.trim($page->getValue('dlinna'));
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$k]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$k]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$k];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$k."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$k."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$k."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$k++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='list-tolst'>";

/////////////Лист Лист толстый////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listgk/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/listgk/PageN/2');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ые", "ая", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Вр", "ВР", $out[$i]);
   	$out[$i] = preg_replace("/(x.*)/", "", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс лист г\к/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'list-tolst', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$l  = $k;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);



	$name = 'Лист г/к';
	$name .=' '.trim($page->getValue('dlinna'));
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$l]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$l]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$l];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$l."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$l."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$l."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$l++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_electrosvar'>";

/////////////Лист Труба электросварная////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/2');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/3');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/4');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

    $out[$i] = str_replace("х/к", "", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаная", "", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Трубы электросварные", "Труба круглая", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс трубы электросварные/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'truba_elektrosvarnaya_cena_za_metr_', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$z = $l;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
	$name = str_replace("D", "", $value['node:text']);
    $name = str_replace("х/к", "", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
    $name = str_replace("стальной", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace(".0", "", $name);
	$name = str_replace("х", "x", $name);
	$name = trim($name);

	$name = str_replace("  ", " ", $name);

	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$z]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$z]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$z];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$z."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$z."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$z."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$z++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='krug'>";

/////////////Круг////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/2');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/3');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/4');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/5');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/6');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/7');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/krug_gk/PageN/8');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("и двутавровые", "а", $out[$i]);
    $out[$i] = str_replace(" М", "М", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаный", "стальной г/к", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Трубы электросварные", "Труба круглая", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс круг/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'krug_stalnoy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$x = $z;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
	$name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);
	$name = trim($name);
	$name = str_replace("  ", " ", $name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
  
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$x]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$x]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$x];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$x."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$x."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$x."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$x++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_prof'>";

/////////////труба профильная////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn_kv/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn_kv/PageN/2');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn_kv/PageN/3');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn_pr/PageN/1');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn_pr/PageN/2');
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn_pr/PageN/3');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("квадратные", "", $out[$i]);
    $out[$i] = str_replace("прямоугольные", "", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаный", "стальной г/к", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Трубы электросварные", "Труба", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$minus = substr($ceni[$i],2,2);
		$ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс труба профильная/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'profilnaya_truba_cena_za_metr_v_roz', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$c = $x;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
	$name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);
	$name = trim($name);
	$name = str_replace("  ", " ", $name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
  
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$c]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$c]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$c];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$c."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$c."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$c."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$c++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='proflist_oz'>";

/////////////профнастил Н57////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/profnastil_oz/mark/3');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}

///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(ТУ.*)/", "", $out[$i]);
    $out[$i] = str_replace("Профнастил оцинкованный", "Профлист Оц. Н57", $out[$i]);
    $out[$i] = str_replace("прямоугольные", "", $out[$i]);
    $out[$i] = str_replace(" Б", "Б", $out[$i]);
    $out[$i] = str_replace(" Ш", "Ш", $out[$i]);
    $out[$i] = str_replace(" К", "К", $out[$i]);
   	$out[$i] = preg_replace("/(АСЧМ.*)/", "", $out[$i]);
   	$out[$i] = str_replace("горячекатаный", "стальной г/к", $out[$i]);
 	$out[$i] = str_replace("стальная", "", $out[$i]);
 	$out[$i] = str_replace("ДУ", "", $out[$i]);
 	$out[$i] = str_replace("инкованная", "", $out[$i]);
 	$out[$i] = str_replace("Трубы электросварные", "Труба", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
// var_dump($out);
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+)/g',$f, $out2);
// var_dump($out2);
	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		$ceni[$i] = preg_replace("/([&nbsp;]+)/s", "", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		$ceni[$i] = str_replace(",", ".", $ceni[$i]);
		$ceni[$i] = str_replace(" ", "", $ceni[$i]);
		// $minus = substr($ceni[$i],2,2);
		// $ceni[$i] = str_replace($minus, "", $ceni[$i]);
	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс труба профильная/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'profnastil_krovelniy', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$v = $c;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
	$name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
	$name = str_replace("  ", " ", $name);
	$name = str_replace("стальная", "", $name);
	$name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);
	$name = trim($name);
	$name = str_replace("  ", " ", $name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$v]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$v]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$v];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$v."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$v."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$v."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$v++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_bessh'>";

/////////////труба бесшовная////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/1');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/2');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/3');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/4');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/5');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/6');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/7');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/8');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/9');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_gdk/PageN/10');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_hdk/PageN/1');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_hdk/PageN/2');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_hdk/PageN/3');
$text .= file_get_contents( 'http://www.mc.ru/page.asp/metalloprokat/truba_hdk/PageN/4');

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);
preg_match_all('/<td class="goods">(.*?)<\/td>/s',$text,$out);
$out=$out[0];
	$count = count($out);
for ($i=1; $i < $count; $i=$i+2) { 
            unset($out[$i]);
        }
$out=array_values($out);
foreach ($out as $key => $value) {
	$out[$key] = iconv('windows-1251', 'UTF-8',$value);
	$out[$key] = str_replace('<td class="goods">', '', $out[$key]);
}
///////////Имя в прайсе/////////////////с сайта mc.ru
for ($i=0; $i < count($out); $i++) { 
	$out[$i] = substr($out[$i], 2);
	$out[$i] = substr($out[$i], 0, -25);
	$out[$i]  = iconv('UTF-8', 'windows-1251', $out[$i]);
	$out[$i] = preg_replace("/(ГОСТ.*)/s", "", $out[$i]);

	$out[$i] = preg_replace("/(x.*)/", "", $out[$i]);
    $out[$i] = str_replace("Трубы г/д", "Труба г/д бесшовная", $out[$i]);
    $out[$i] = str_replace("Трубы х/д", "Труба х/д бесшовная", $out[$i]);

    $out[$i] = str_replace("прямоугольные", "", $out[$i]);

  	$out[$i] = str_replace("  ", " ", $out[$i]);


}
// var_dump($out);
preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);

$out1 = $out1[0];

$ceni = [];
for ($i=0; $i < count($out1); $i++) { 
 	$f = $out1[$i];
 	// $f = htmlspecialchars($f);
 	// echo '<textarea>'.($f)."</textarea><br/>";
	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);

	$ceni[$i] = $out2[1];
    $ceni[$i] = trim($ceni[$i]);

    $ceni[$i] = preg_replace("/\s/", "", $ceni[$i]);
}

$ceni = array_diff($ceni, array(""));

	$ceni = array_values($ceni);
	for ($i=0; $i < count($ceni); $i++) { 
		preg_match_all("/([0-9]+)/", $ceni[$i], $tmp);
		$cena = $tmp[0][0].$tmp[0][1];
		$ceni[$i] = $cena;

	}
// var_dump($ceni);


		$price = [];
for ($i=0; $i < count($ceni); $i++) { 
	$out[$i] = trim($out[$i]);
	$price[$out[$i]] = trim($ceni[$i]);
	}

	/////////Прайс труба профильная/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'truba-besshovnaya', null, null, 2));
$objects = $objects['lines']['nodes:item'];
/////////Имя на сайте.../////////////
$b = $v;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
	$name = str_replace("D", "", $value['node:text']);
    $name = str_replace("А 3", "А3", $name);
    $name = str_replace("  ", " ", $name);
    $name = str_replace(".0", "", $name);
    $name = str_replace("№-", "", $name);
	$name = str_replace("  ", " ", $name);
	// $name = str_replace("х", "x", $name);
	$name = str_replace("ду", "", $name);
	$name = trim($name);
	$name = str_replace("  ", " ", $name);
	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';
	// var_dump($price);
   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$b]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$b]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$b];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$b."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$b."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$b."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$b++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr>';
// ?>
<?php
echo "</div><div id='truba_krug'>";

/////////////тонкостенная труба////////////
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
$text = file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn' );
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/2' );
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/3' );
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/4' );
$text .= file_get_contents( 'http://mc.ru/page.asp/metalloprokat/truba_svarn/PageN/5' );

$text = iconv('windows-1251', 'UTF-8',$text);
$json = file_get_contents(__dir__.'/nacenka.json');
$nacenka = json_decode($json);

preg_match_all('/<tr(.*?)<\/tr>/s',$text,$out1);
$out1 = $out1[0];
$price = [];


for ($i=0; $i < count($out1); $i++) { 
		$f = $out1[$i];
	preg_match('/<td class="goods">(.*?)<\/td>/s',$f,$out);
$out=$out[0];

	$out = str_replace('<td class="goods">', '', $out);

	$out = substr($out, 2);
	$out = substr($out, 0, -25);
	$out = str_replace("х/к", "", $out);

// echo $out;

	preg_match('/([0-9]+ [0-9]+)/',$f, $out2);
	$ceni = $out2[0];
    $ceni = trim($ceni);

    $ceni = preg_replace("/\s/", "", $ceni);
	$ceni = preg_replace("/([&nbsp;]+)/s", "", $ceni);
	$ceni = str_replace(" ", "", $ceni);
	$minus = substr($ceni,2,2);
	$ceni = str_replace($minus, "", $ceni);
	$ceni = trim($ceni);
	$out = trim($out);

	if(empty($price[$out]) | $ceni > $price[$out]){
		$price[$out] = $ceni;
	}
	

}



	/////////Прайс труба тонкостенная/////////////

$page = $variables['full:page'];
$objects = $this->macros('catalog', 'getObjectsList', array(null, 'trubi_tonkostennie_elektrosvarnie', null, null, 2));
$objects = $objects['lines']['nodes:item'];
$m = $b;
foreach ($objects as $key=>$value){

  $page = $this->getPageById($value['attribute:id']);
  $name = str_replace("Труба тонкостенная", "Трубы электросварные", $value['node:text']);

    $name = str_replace(",0", "", $name);
    $name = str_replace(",", ".", $name);
    $name = str_replace("х/к", "", $name);
    $name = str_replace("х", "x", $name);
    $name = str_replace("х/к", "", $name);

    $name  = trim($name);
        	// echo '"'.$name.'" : "500",<br>';
	// echo $name.' - '.$price[$name].'<br>';

   
  if($_POST){
		$base[$name]['price'] = $_POST['price'.$m]; 
		$base[$name]['nacenka'] = $_POST['nacenka'.$m]; 
		$base[$name]['nacenka_metr'] = $_POST['nacenka_metr'.$m];
		
	
	}
	 // echo '"'.$name.'" : "500",<br>';
	$nacenka_metr = json_decode($json1);
	echo $name;
	echo "<input type='text' name='price".$m."' value='".$price[$name]."'>наценка на тонну<input type='text' name='nacenka".$m."'value='".$base[$name]['nacenka']."'>наценка на метр<input type='text' name='nacenka_metr".$m."'value='".$base[$name]['nacenka_metr']."'><br>";
	if($_POST){
			
			 $page->setValue('cena_za_tonnu', $base[$name]['price'] += $base[$name]['nacenka']);
			 
			 $cena_metra = round(($price[$name]*$page->getValue('ves_metra')+$base[$name]['nacenka_metr'])*0.001);
			 $page->setValue('cena_za_list', $cena_metra);

		

		$base_json = json_encode($base);
		file_put_contents(__dir__.'/base.json', $base_json);
	}

	$m++;
	
}
If($_POST){echo "<h3>Сохранено</h3>";}

echo	'<hr></div>';
echo "<input type='submit'></form>";

// ?>
<style>
    div {
    	display : none;
    }
    body :target{
    	display: block;
    }
</style> 
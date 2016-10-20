<?
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
if (!empty($_POST['json'])) {

	$f = fopen(__dir__.'/nacenka.json', "w");
	// Записать текст
	fwrite($f, $_POST["json"]); 
	// Закрыть текстовый файл
	fclose($f);
}
$json = file_get_contents(__dir__.'/nacenka.json');
// $nacenka = json_decode($json);
 // var_dump($json);

echo '<form method="POST" action="/nacenki"><textarea rows="10" cols="50" name="json">'.$json.'</textarea><input type="submit" value="сохранить"></form>';

?>
<?
$db_prefix = "kor";

header('Content-Type: text/html; charset=utf-8');
if (!$_POST) exit('Пусто');
include("../config.php");
mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
mysql_select_db(DB_DATABASE);
mysql_query('SET NAMES utf8');


$vars = explode(';', $_POST['vars']);
$values = explode(';', $_POST['values']);
$title = $_POST['title'];
$category = $_POST['category'];

$name = mysql_real_escape_string($title);






		// product_id={$new_id},


//////////////////////////////////////////////// 	oc_product
	$query = "INSERT INTO ".$db_prefix."_product 
				SET
				model='model',
				quantity=1,
				stock_status_id=5,
				shipping=1,
				subtract=0,
				status=1
				";
	$result = mysql_query($query);
//////////////////////////////////////////////// 	oc_product_description

	$query = "SELECT  product_id FROM ".$db_prefix."_product ORDER BY product_id DESC";
	$result = mysql_query($query);
	$res = mysql_fetch_assoc($result);	
	$new_id = $res['product_id']++;
	if (empty($new_id))$new_id = 1;





	$query = "INSERT INTO ".$db_prefix."_product_description 
				SET
				product_id={$new_id},				
				language_id=1,
				name='{$name}',
				description=''
				";
	$result = mysql_query($query);
//////////////////////////////////////////////// 	oc_product_to_store
	$query = "INSERT INTO ".$db_prefix."_product_to_store 
				SET
				product_id={$new_id},				
				store_id=0
				";
	$result = mysql_query($query);

//////////////////////////////////////////////// 	oc_product_to_category
	$query = "INSERT INTO ".$db_prefix."_product_to_category 
				SET
				product_id={$new_id},				
				category_id={$category}
				";
	$result = mysql_query($query);

/////////////////////////////////////////////////// attribute
	foreach ($vars as $key => $var) {
		$query = "SELECT attribute_id FROM ".$db_prefix."_attribute_description WHERE name='".$var."'";
		$result = mysql_query($query);
		$res = mysql_fetch_assoc($result);
		$attrid = $res['attribute_id'];

		if (empty($attrid)) continue;
		$query = "INSERT INTO ".$db_prefix."_product_attribute 
					SET
					product_id={$new_id},				
					language_id=1,
					attribute_id={$attrid},
					text='{$values[$key]}'
					";
		$result = mysql_query($query);
		//echo $query;

	}
	echo $title." готово";
?>
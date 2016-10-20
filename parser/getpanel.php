<?
header("Cache-Control: no-store, no-cache, must-revalidate");
header('Content-Type: text/html; charset=utf-8');

include("../config.php");
mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
mysql_select_db(DB_DATABASE);
mysql_query('SET NAMES utf8');

$query = "SELECT  * FROM kor_category_description";
$result = mysql_query($query);
?>
<div id="newpanel">
	<select id="cat">
	<?
	while ($res = mysql_fetch_assoc($result)) {
		echo '<option value="'.$res['category_id'].'">'.$res['name'].'</option>';
	}
	?>
	</select>
    <a id="import" class="link" href="#">Импорт</a>
</div>
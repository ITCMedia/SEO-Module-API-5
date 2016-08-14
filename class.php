<?php
// Версия 1.2.4
// 23.11.2015
// Обновление главной страницы, корневых директорий, информационных систем, магазина
class Updater{
	public $informationsystem_id, $shop_id, $structure_id;
	
	public function getStructure($url_level_1){
		$query_selection = mysql_query("SELECT `structure_id` FROM `structure_table` WHERE `structure_path_name` ='{$url_level_1}'");
		$result = mysql_fetch_array($query_selection); // Преобразование выборки в ассоциативный массив для дальнейшего взятия ячейки.
		$structure_id = $result['structure_id'];
		// echo '<br>ID Структуры: ' .$structure_id;
		return $structure_id;
	}
	
	public function updateStructure($deepLevel, $title, $description, $keywords){
		$result = mysql_query ("UPDATE `structure_table` SET `structure_title`='{$title}', `structure_description`='{$description}', `structure_keywords`='{$keywords}'  WHERE `structure_path_name` ='{$deepLevel}'");
		if ($result == 'true')
		{
		//	echo "<br>Данные для корневого узла " . $deepLevel . " успешно обновлены.";
		}
	}
	
	public function updateFirstLevel($url_level_1, $title, $description, $keywords){
		$result = mysql_query ("UPDATE `structure_table` SET `structure_title`='{$title}', `structure_description`='{$description}', `structure_keywords`='{$keywords}'  WHERE `structure_path_name` ='{$url_level_1}'");
		if ($result == 'true')
		{
		//	echo "<br>Данные для корневого узла " . $url_level_1 . " успешно обновлены.";
		}
	}
	
	public function updateMainpage($title, $description, $keywords){
		$result = mysql_query ("UPDATE `structure_table` SET `structure_title`='{$title}', `structure_description`='{$description}', `structure_keywords`='{$keywords}'  WHERE `structure_path_name` ='/'");
	}
	
	public function checkSystem($current_structure){
		$systemReturn = array();
		$query_selection = mysql_query("SELECT `information_systems_id` FROM `information_systems_table` WHERE `structure_id` ='{$current_structure}'");
		$result = mysql_fetch_array($query_selection);
		$systemReturn['is_id'] = $result['information_systems_id'];
		if($systemReturn['is_id'] == ''){
			$query_selection = mysql_query("SELECT `shop_shops_id` FROM `shop_shops_table` WHERE `structure_id` ='{$current_structure}'");
			$result = mysql_fetch_array($query_selection); 
			$systemReturn['shop_id'] = $result['shop_shops_id'];
			//echo '<br>ID Магазин: ' .$systemReturn['shop_id'];
		}else{
			//echo '<br>ID ИС: ' .$systemReturn['is_id'];
		}
		return $systemReturn;
	}
	
	public function shopUpdate($deepLevel, $shop_id, $title, $description, $keywords){
		$query_item = mysql_query("SELECT `shop_groups_id` FROM `shop_items_catalog_table` WHERE `shop_items_catalog_path` ='{$deepLevel}' AND `shop_shops_id` ='{$shop_id}'");
		$output = mysql_fetch_array($query_item); 
		$shop_group_id = $output['shop_groups_id'];
		if($shop_group_id != ''){
			$result = mysql_query ("UPDATE `shop_items_catalog_table` SET `shop_items_catalog_seo_title`='{$title}', `shop_items_catalog_seo_description`='{$description}', `shop_items_catalog_seo_keywords`='{$keywords}'  WHERE `shop_items_catalog_path` ='{$deepLevel}' AND `shop_shops_id` ='{$shop_id}'");
			if ($result == 'true')
			{
			//	echo "<br>Данные для товара " . $deepLevel . " успешно обновлены.";
			}
		}else{
			$result = mysql_query ("UPDATE `shop_groups_table` SET `shop_groups_seo_title`='{$title}', `shop_groups_seo_description`='{$description}', `shop_groups_seo_keywords`='{$keywords}'  WHERE `shop_groups_path` ='{$deepLevel}' AND `shop_shops_id` ='{$shop_id}'");
			if ($result == 'true')
			{
			//	echo "<br>Данные для группы магазина " . $deepLevel . " обновлены.";
			}
		}
	}
	
	public function isUpdate($deepLevel, $informationsystem_id, $title, $description, $keywords){
		$query_item = mysql_query("SELECT `information_groups_id` FROM `information_items_table` WHERE `information_items_url` ='{$deepLevel}' AND `information_systems_id` ='{$informationsystem_id}'");
		$output = mysql_fetch_array($query_item); // Преобразование выборки в ассоциативный массив для дальнейшего взятия ячейки.
		$informationsystem_group_id = $output['information_groups_id'];
		if($informationsystem_group_id != ''){
			$result = mysql_query ("UPDATE `information_items_table` SET `information_items_seo_title`='{$title}', `information_items_seo_description`='{$description}', `information_items_seo_keywords`='{$keywords}'  WHERE `information_items_url` ='{$deepLevel}' OR `information_items_id` ='{$deepLevel}' AND `information_groups_id` ='{$informationsystem_id}'");
			if ($result == 'true')
			{
			//	echo "<br>Данные для инфоэлемента " . $deepLevel . " успешно обновлены.";
			}
		}else{
			$result = mysql_query ("UPDATE `information_groups_table` SET `information_groups_seo_title`='{$title}', `information_groups_seo_description`='{$description}', `information_groups_seo_keywords`='{$keywords}'  WHERE `information_groups_path` ='{$deepLevel}' AND `information_systems_id` ='{$informationsystem_id}'");
			if ($result == 'true')
			{
			//	echo "<br>Данные для группы " . $deepLevel . " обновлены.";
			}
		}
	}
}
?>
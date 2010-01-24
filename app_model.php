<?php

class AppModel extends Model {
	
	var $cacheSources = true;
	var $cacheQueries = true;
	
	function imageCheck($image) {		
		$large = PRODUCT_IMAGES . str_replace('http://images.sextoysex.com/', '', $image);
		if (file_exists($large)) return 1;
		else return 0;
	}
}
?>
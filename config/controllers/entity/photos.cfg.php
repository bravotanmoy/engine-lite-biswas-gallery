<?php

$config = array(
	'container_table' => 'lite_photo_containers',
	'container_key' => 'gallery_id',
	'sort_by' => 'position',
	//kaip vadinasi parametras (atejes is posto), kuriame irasyti redaguoti elementai
	'element_param_name' => 'photo',
	//kaip vadinasi parametras (atejes is posto), kuriame yra nauji elementai
	'new_element_param_name' => 'new_photo',
	'gallery' => array(
		'width' => 2000,
		'height' => 2000,
		'prefix' => time(),
		'path' => 'images/galleries/',
		'max_size' => IMAGE_MAX_SIZE,
	),
	'sizes' => array(
	/*
	  'thumb' => array(
	  'width' 	=> 100,
	  'height' 	=> 75,
	  'mode' 		=> 'crop',
	  'fill' 		=> true,
	  'bgcolor' 	=> 'ffffff',
	  ),
	 */
	),
	// TODO:
	'show_description' => false,
);
?>
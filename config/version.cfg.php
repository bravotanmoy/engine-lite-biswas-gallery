<?php

/**
 * Sistemos engine versijos ir copyright žinutės konfigūracija.
 * 
 */
$config = array(
	'version' => '3.0',
	//TODO: svarbu nustatyti versijos datą, kad būtų tvarkingai paimami resursai.
	
	
	'title' => t('Turinio Valdymo Sistema „e-ngine“'),
	'elab_description' => t('UAB „Electronic lab“ - Interneto svetainės, specializuotos sistemos, elektroninė komercija, programavimo darbai'),
);

if (PROJECT_MODE == 'development'){
	$config['date'] = rand(1, 1000);
}else{
	$config['date'] = '201708';
}

$config['copyright'] = "<p><a href=\"http://www.engine.lt\" target=\"_blank\" title=\"$config[title]\">$config[title]</a> v. $config[version]</p>
				 <p>&copy;&nbsp;<a href=\"http://www.e-lab.lt\" title=\"$config[elab_description]\" target=\"_blank\"> e-Lab</a>, 2007 - " . date('Y') . "</p>";

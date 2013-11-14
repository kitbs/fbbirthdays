<?php
if (stripos($_SERVER['PHP_SELF'], 'functions.php') !== false ) { http_response_code(404); die(); }

include 'vendor/autoload.php';
use Sabre\VObject;

function saveconfig( $url = false, $exclude = false, $include = false ) {
	if (is_array($exclude)) {
		$exclude = '\''.implode( '\',
			\'', $exclude ).'\'';
	}
	if (is_array($include)) {
		$include = '\''.implode( '\',
			\'', $include ).'\'';
	}

	$baseconfig = '<?php
	if (stripos($_SERVER[\'PHP_SELF\'], \'config.php\') !== false ) { http_response_code(404); die(); }

	$url = \''.$url.'\';

	$include = array('.$include.');

	$exclude = array('.$exclude.');';

	file_put_contents( 'config.php', $baseconfig );
}

function getcalendar($url) {

	$cache = 'cache/'.md5($url);

	if (file_exists($cache)) {
		$data = file_get_contents($cache);
	}
	else {
		$data = file_get_contents($url);
		file_put_contents($cache, $data);
	}

	return VObject\Reader::read($data);
}

function getfilteredcalendar($config) {

	$cache = 'cache/'.md5(serialize($config));

	if (file_exists($cache)) {
		return file_get_contents($cache);
	}
}
function savefilteredcalendar($calendar, $config) {
	$cache = 'cache/'.md5(serialize($config));
	file_put_contents($cache, $calendar);
}
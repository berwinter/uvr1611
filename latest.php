<?php
include_once("lib/backend/uvr1611-connection.inc.php");
include_once("lib/backend/piko-connection.inc.php");

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

$data = load_cache("uvr1611_latest", Config::getInstance()->app->latestcache);

$debug = 0;
if ($argc > 1) {
	//debug --> echo only when an additional input received
	$debug = 1;
}

if(!$data)
{
	//UVR1611
	try{
		$uvr = Uvr1611::getInstance();
		$gdata = $uvr->getLatest();
		if ($debug > 0) {
			echo "latest.php - connection to BL-Net --> UVR1611!\n";	
		}		
	}
	catch (Exception $e) {
		if ($debug > 0) {
			echo "latest.php - No connection to BL-Net --> UVR1611!\n";	
		}
	}	
	//PIKO
	try{
		$piko = Piko5::getInstance();				
		if ($piko->fetchData()){			
			 $myAData = $piko-> getArrValues();			
			 $frame = $myAData["frame"];
			/* must be convertet to string, 
			   otherwise in the schema the values will not be shown */				 
			 $gdata[$frame] = $myAData;
			if ($debug > 0) {
				echo "latest.php - connection to PIKO!\n";	
			}		
		} else {
			if ($debug > 0) {
				echo "latest.php - No connection to PIKO!\n";	
			}		
		}
	}	
	catch (Exception $e) {
		if ($debug > 0) {
			echo "latest.php - No connection to PIKO!\n";	
		}		
	}
	
	$data = json_encode($gdata);
	save_cache($data,"uvr1611_latest");
} else {
	if ($debug > 0) {
		echo "no new Data\n";
	}
}
echo $data;



function save_cache($data, $key) {
	$key = md5($key);
	$data = serialize($data);
	file_put_contents('/tmp/'.$key, $data);
}

function load_cache($key, $expire) {
	$key = md5($key);
	$path = '/tmp/'.$key;
	if(file_exists($path))
	{
		if(time() < (filemtime($path) + $expire)) {
			return unserialize(file_get_contents($path));
		}
		unlink($path);
	}
	return false;
}

<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');
$data = load_cache("uvr1611_latest", 60);

if(!$data)
{
 $data = exec('uvr1611-logger -a 10.0.0.100');	
 save_cache($data,"uvr1611_latest");
}

print $data;



function save_cache($data, $key) {
	$key = md5($key);
    $data = serialize($data);
    file_put_contents('/tmp/'.$key, $data);        
}

function load_cache($key, $expire) {
        $key = md5($key);
        $path = '/tmp/'.$key;
        if(file_exists($path) && time() < (filemtime($path) + $expire)) {
            return unserialize(file_get_contents($path));
        }
        unlink($path);
        return false;
}

?>

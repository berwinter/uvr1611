<?php
include_once("lib/backend/uvr1611-connection.inc.php");
$uvr = Uvr1611::getInstance();
echo $uvr->getCount();

<?php
include_once("lib/backend/uvr1611.inc.php");
$uvr = Uvr1611::getInstance();
echo $uvr->getCount();

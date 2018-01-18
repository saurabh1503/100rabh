<?php 
$str=file_get_contents('shopzilla-data-feed.txt');

//replace something in the file string - this is a VERY simple example
$str=str_replace(",", "  ",$str);
$str=str_replace('"', "",$str);
//write the entire string
file_put_contents('shopzilla-data-feed.txt', $str);	

?>

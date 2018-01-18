<?php
// $serverName = "12.207.213.14"; //serverName\instanceName
			// $connectionInfo = array("Database" => "efloors_backend_test", "UID" => "chetu", "PWD" => "ch3tuf0ry0u");
			// $conn = sqlsrv_connect($serverName, $connectionInfo);
			// if ($conn) {
				// echo "Connection established.<br />";
			// } else {
				// echo "Connection could not be established.<br />";
				// die(print_r(sqlsrv_errors(), true));
			// }
			
			
			
			
// $serverName = "12.207.213.14";  

/* Connect using Windows Authentication. */  
try  
{  
  $conn = new PDO('mysql:host=12.207.213.14;dbname=efloors_backend_test', 'chetu', 'ch3tuf0ry0u', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')) 
  $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
}  
catch(Exception $e)  
{   
  die( print_r( $e->getMessage() ) );   
} 
			
?>



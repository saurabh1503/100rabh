<?php
ini_set('display_errors','On');
require('class.csvCRUD.php');

//////////////////////
// Define Table Headers:
// RowNum is automatically added by the class. It refers to the 0-indexed line number of the CSV and you must include it when specifying custom headers
// You can not manipulate the RowNum, but you can utilitze the column for admin actions such as delete and edit. These actions would be defined the in the template columns array.
// All other columns are referred to alphabetically like a spreadsheet.
//////////////////////
$tbl_headers = array('RowNum'=>'Row:','A'=>'UPC:','B'=>'EAN:','C'=>'Type:','D'=>'Country:','E'=>'Genre:','F'=>'Price:','G'=>'Descrip:','H'=>'MOD:');


$csv = new csvCRUD('example.txt','|');
$csv -> set_custom_tbl_headers($tbl_headers);
$tbl = $csv -> get_tbl_html();//after all desired options set, retrieve the reulting query HTML.

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>

<head>
    <title>DVD Collection</title>
    <script language="Javascript" type="text/javascript" src="js/sorttable.js"></script>
    <style>

body {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 13px;
}
#form_wrapper { 
 margin: 0 auto;
 width: 372px;
}
#form_content { 
 width: 350px;
 color: #333;
 border: 1px solid #ccc;
 background: #F2F2E6;
 margin: 10px 0px 10px 0px;
 padding: 10px;
 /*
 height: 300px;
 */
}

#tbl_wrapper { 
 margin: 0 auto;
 width: 90%;
}
#tbl_content { 
 color: #333;
 border: 1px solid #ccc;
 background: #F2F2E6;
 margin: 10px 0px 10px 0px;
 padding: 10px;
 /*
 height: 300px;
 */
}
        #csvtbl{font-family: arial,helvetica,verdana,sans-serif; font-size:12pt;}
        #tbl_div{margin-right:auto; margin-left:auto;}
        .csvtbl_row_odd{}
        .csvtbl_row_even{background-color:#D4DBE6;}
        .td_4{font-weight:bold;}
    </style>
</head>
<body>
<div id="container">
    <?php
		echo "
		$tbl
		";

    ?>
</div>
</body>
</html> 

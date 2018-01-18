$input = fopen('Database/Users.csv', 'r');  //open for reading
$output = fopen('Database/temporary.csv', 'w'); //open for writing
while( false !== ( $data = fgetcsv($input) ) ){  //read each line as an array

   //modify data here
   if ($data[4] == $_POST['oldPassword'] && $data[1] == $_SESSION['username']) {
      //Replace line here
      $data[4] = $_POST['newPassword'];
      echo("SUCCESS|Password changed!");
   }

   //write modified data to new file
   fputcsv( $output, $data);
}

//close both files
fclose( $input );
fclose( $output );

//clean up
unlink('Database/Users.csv');// Delete obsolete BD
rename('Database/temporary.csv', 'Database/Users.csv');
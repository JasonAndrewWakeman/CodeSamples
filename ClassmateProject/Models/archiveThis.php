<?php




	require_once('mysqlWeb.php');
           
       

 
	$returnData = array();
	$returnData['wasSuccessful'] = "false";
	//checks to make sure the _Post was achieved via the hidden field 'name' of the button which submitted the information to post.
	    if(empty($_POST['tempString'])){
	        // Adds name to array
	        $returnData['wasSuccessful'] = "false";
		echo json_encode($returnData); 
		exit();
	 
	    }
	    else{
		$noteToArchive = $_POST['tempString'];
		$u_ID = 1; //user id should be set by session variable
    		$c_ID = 1; //course id should be set by session variable
		$f_NAME = $u_ID . $c_ID . time(); 
		$f_TYPE = "text";
	    }
	
	  

//a new entry to the 'archive' table is created using the mysqli libraries to prevent php injection. It uses some method to combine all the required paramaters and another method to execute the statement.

         $query = "INSERT INTO archive (archive_id, archive_user_id, archive_course_id, file_name, file_type, file_LOCATION, date_created, date_modified, date_deleted) VALUES (NULL, ?, ?, ?, ?, ?, NOW(), NULL, NULL)";
         
          $stmt = mysqli_prepare($dbc, $query);
           

          //i Integers
          //d Doubles
          //b Blobs
          //s Everything Else
           
          mysqli_stmt_bind_param($stmt, "iisss", $u_ID, $c_ID, $f_NAME, $f_TYPE, $noteToArchive);
          
          mysqli_stmt_execute($stmt);

          $affected_rows = mysqli_stmt_affected_rows($stmt);
           
          if($affected_rows == 1){
               
             // echo 'Correctly entered into DATABASE! :)';
              $returnData['wasSuccessful'] = "true";
              mysqli_stmt_close($stmt);
               
              mysqli_close($dbc);
               
          } else {
              echo ' An error Occurred<br />';
              echo mysqli_error();
               
              mysqli_stmt_close($stmt);
               
              mysqli_close($dbc);
          }
         
  		echo json_encode($returnData); 
		exit();


?>
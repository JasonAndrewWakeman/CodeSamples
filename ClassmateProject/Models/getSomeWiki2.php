
<?php



	require_once('mysqlWeb.php');
           
       

    $u_ID = 1; //user id should be set by session variable!
    $textBuffer = "";
	$returnData = array();
	$returnData['wasSuccessful'] = "false";
	$charsWritten = 0;
	$maxCharsToWrite= 900;
	//checks to make sure the _Post was achieved via the hidden field 'name' of the button which submitted the information to post.
	    if(empty($_POST['search_term'])){
	        // Adds name to array
	        $missingValues[] = 'Search Term';
	        $returnData['wasSuccessful'] = "false";
		echo json_encode($returnData); 
		exit();
	 
	    } else {

	        // Trim white space from the name and store the name
	        $searchTerm = trim($_POST['search_term']);
	        //replace any amount of spaces with underscores
	 		$searchTerm = preg_replace('/\s+/', '_', $searchTerm);

	    }
	
	   $searchURL = "http://en.wikipedia.org/wiki/" . $searchTerm;

	   $file = fopen($searchURL, "r");
	   if(!($file)){
 		$returnData['wasSuccessful'] = "false";
		echo json_encode($returnData); 
		exit();
	 
	   }
	   //opens the file to write to %%%%%%%SHOULD BE a variant of searchTerm or timestamp$$$$$$$$
	  // $writtenFile = fopen("testText.txt","w");



		if (is_null($file)) {
			echo "the man";
		}
		else{
			//echo "the file is not null";
		
 			$returnData['wasSuccessful'] = "true";
			//while the end of file is not reached...
			while (!feof($file))
			{
				//saves all strings fitting a <p> </p> tag before the next newline and puts the complete one in arrayOfParagraphs[0]
				preg_match("%(^(<p>)(.*)+?(</p>)$)%", fgets($file), $arrayOfParagraphs);	

				//if a match was found in the file...
				if (!(empty($arrayOfParagraphs))){
			
					//sets stringToWrite to the next iterated paragraph needing to be written
					$stringToWrite = $arrayOfParagraphs[0];
					//removes html and tags
					$stringToWrite = strip_tags($stringToWrite);
					$stringToWrite = "&nbsp;&nbsp;&nbsp;&nbsp;" . $stringToWrite;
					//if the string is non empty...
					if(!(empty($stringToWrite))){	

						//updates the total number of characters written after the write. 
						$charsWritten = $charsWritten + strlen($stringToWrite);

						//if potential 'charswritten" exceeds maxCharsToWRite %%%%SHOULD BE CONSTANT%%%%%%%: BREAK!
				
						if($charsWritten > $maxCharsToWrite) {

							//sets number of charsWritten back to the actual number of characters written to text buffer
							$charsWritten = $charsWritten - strlen($stringToWrite);
							//sets charsNeeded to the exact number of chars still needed to get to the desired num of chars
							$charsNeeded = $maxCharsToWrite - $charsWritten;
							//writes the exact number of characters needed to the tempString
							$tempString = substr($stringToWrite, 0, $charsNeeded);
							//appends the tempString to the textBuffer containing the text to return
							$textBuffer = $textBuffer . $tempString; 
							
							break;
						} 
						else{
						//writes the string to file with an empty line after it 
							$textBuffer = $textBuffer . $stringToWrite. "<br />" ; 
						//	fwrite($writtenFile, "$stringToWrite\n\n", 5000 );
						}

					}
				}
			}				
		}

//a new entry to the 'enrollment' table is created using the mysqli libraries to prevent php injection. It uses some method to combine all the required paramaters and another method to execute the statement.

         $query = "INSERT INTO wikiSearches (user_id, term, text, wikiSearch_id) VALUES (?, ?, ?, NULL)";
         
          $stmt = mysqli_prepare($dbc, $query);
           

          //i Integers
          //d Doubles
          //b Blobs
          //s Everything Else
           
          mysqli_stmt_bind_param($stmt, "iss", $u_ID, $searchTerm, $textBuffer);
          
          mysqli_stmt_execute($stmt);

          $affected_rows = mysqli_stmt_affected_rows($stmt);
           
          if($affected_rows == 1){
               
             // echo 'Correctly entered into DATABASE! :)';
               
              mysqli_stmt_close($stmt);
               
              mysqli_close($dbc);
               
          } else {
              echo ' An error Occurred<br />';
              echo mysqli_error();
               
              mysqli_stmt_close($stmt);
               
              mysqli_close($dbc);
          }
         
   /*$arr = array(
        array(
                "first_name" => "Darian",
                "last_name" => "Brown",
                "age" => "28",
                "email" => "darianbr@example.com"
        ),
        array(
                "first_name" => "John",
                "last_name" => "Doe",
                "age" => "47",
                "email" => "john_doe@example.com"
        )
);
*/



		$returnData['text'] = $textBuffer;

/* encode the array as json. this will output [{"first_name":"Darian","last_name":"Brown","age":"28","email":"darianbr@example.com"},{"first_name":"John","last_name":"Doe","age":"47","email":"john_doe@example.com"}] */

  		echo json_encode($returnData); 
		exit();
		
?>

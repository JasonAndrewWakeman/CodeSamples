package proj263;

//Section 0. Import Java.sql package
import java.sql.*;

public class JDBC_2 {

public static void main (String [] args) throws Exception {

  

   // Section 0.1: Load the driver 
   try {   
      // Load the driver (registers itself)
      Class.forName("com.mysql.jdbc.Driver");
      } 
   catch (Exception E) {
         System.err.println ("Unable to load driver.");
         E.printStackTrace ();
   } 

   try { 

      // Section 0.2. Connect to the database
      Connection conn1; // An object of type connection 
      String dbUrl = "jdbc:mysql://csdb.cs.iastate.edu:3306/jwakemanDB";
      String user = "jwakeman";
      String password = "jwakeman-98";
      conn1 = DriverManager.getConnection (dbUrl, user, password);
      System.out.println ("*** Connected to the database ***"); 

      
      // ***************************************************************
      // A. Report Salaries of each Instructor and total salary of all Instructors
      // ***************************************************************
      // Section A.1 Create stmt1 as a new createstatement()
      
      Statement stmt1 = conn1.createStatement ();

      // Section A.2 Execute a query, receive result in a result set 
      ResultSet rs1 = stmt1.executeQuery ("select p.Name, i.Salary"  + " " + 
                                          "from Person p, Instructor i" + " " + 
                                          "where p.ID = i.InstructorID "); 

      
      //Section A.3  Print a header for the report:
      System.out.println ( );		
      System.out.println ("Name           Salary");		
      System.out.println ("----           ------");		

      //Section A.4  Print report from the result set:

      
      double totalPayroll = 0.0; 

      String iName; // To store value of Name attribute 
      double iSalary; // To store value of Salary attribute
      while(rs1.next()) {
         // Access and print contents of one tuple
         iName = rs1.getString (1); // Value accessed by its position
         iSalary = rs1.getInt ("Salary"); // Access by attribute Name
         System.out.println (iName + "   " + iSalary);
       
         totalPayroll = totalPayroll + iSalary;
      }			

      //Section A.5 Print report for totalPayroll: 
      System.out.println ( ); 
      System.out.println ( );		
      System.out.println ("Total payroll for Instructors: $" + totalPayroll);

   
   // ***************************
   // B. Using Create Statement  
   // ***************************
    //  selects IDs of students, their classification, MentorID, and GPA in descending order of GPA-values.
    //  Then write java code to go through the result set of the query and and insert the information for top 20 (or more)
    //  students into the MeritList table. Note that this list may contain less than 20 distinct GPA-values and more than
 //20 students. This is because some students may have the same GPA. After having taken top 20 students into account,
 //you should include those students who have the same GPA as the 20th student. 
      
      
      
      //Section B.1: first create a MeritList Table

      String createString;
      Statement stmtB;
		createString = "CREATE TABLE MeritList (" +
							"StudentID INTEGER, " +
							"Classification VARCHAR(30), " +
							"MentorID INTEGER, " +
							"GPA DOUBLE)";
		
			stmtB = conn1.createStatement();
	   		stmtB.executeUpdate(createString);
			stmtB.close();  
      
			
      
      // Section B.2: Execute a query, receive result in a result set
	  
	  Statement stmtB2 = conn1.createStatement ();
		      
      ResultSet rsb = stmtB2.executeQuery ("SELECT s.StudentID, s.Classification, s.MentorID, s.GPA"  + " " + 
                                          "FROM Student s" + " " + 
                                          "ORDER BY GPA desc "); 
      
      // Section B.3: Process the result set 

     
 
     double gpa2;
     int MeritCount = 0;
      double gpa = 0; // To store value of GPA attribute
      while(rsb.next() && MeritCount != -1)//MeritCount is set to -1 as soon as we find a student with a lower gpa than the 20th highest gpa
      {
   	  
    	  if (MeritCount >= 20) //only performed when we have already added the first at least 20 students to meritlist
    	  { 
    	      gpa2 = rsb.getDouble ("GPA"); // Access by attribute Name
    	         
    	      if(gpa2 == gpa)//only performed if we find more students with gpa equal to that of the 20th highest performing student
    	      {
    	    	  
    	    	//create an update statement via PreparedStatement and then replace each question mark with a value from the tuple
    	    		PreparedStatement stmtB3 = conn1.prepareStatement ("INSERT INTO MeritList"  + " " + 
    	    	              "VALUES (?, ?, ? , ? )"); 
    	 
    	    		stmtB3.setString(1, rsb.getString (1));
    	    	    stmtB3.setString(2, rsb.getString (2));
    	    	    stmtB3.setInt(3, rsb.getInt (3));
    	    	    stmtB3.setDouble(4, gpa2);
    	    	   	stmtB3.executeUpdate();		
    	    	   	stmtB3.close();
    	           
    	         }
    	         
    	      else MeritCount = -1;
    	 }
    	 else{
    		  
         //create an update statement via PreparedStatement and then replace each question mark with a value from the tuple
    		PreparedStatement stmtB3 = conn1.prepareStatement ("Insert INTO MeritList"  + " " + 
    	              "VALUES (?, ?, ? , ? )"); 
 
    		stmtB3.setString(1, rsb.getString (1));
    	    stmtB3.setString(2, rsb.getString (2));
    	    stmtB3.setInt(3, rsb.getInt (3));
    	    stmtB3.setDouble(4, rsb.getDouble (4));
    	   	stmtB3.executeUpdate();		
    	   	stmtB3.close();
    	    	
            gpa = rsb.getDouble ("GPA"); // Access by attribute Name
            MeritCount ++;
    	 }
       

      }
      stmtB2.close (); 

      
      // ***************************
      // C. Printing the contents of MeritList  
      // ***************************

      
      //Query the new MeritList table and send * to resultlist
      Statement stmtC = conn1.createStatement ();
      ResultSet rsC= stmtC.executeQuery ("SELECT *"  + " " + 
              "FROM MeritList m" + " " + 
              "ORDER BY m.GPA "); 
      
     
    //Print a header for the report:
      System.out.println ( );		
      System.out.println ("StudentID      Classification     MentorID     GPA");		
      System.out.println ("--------       --------------     --------     ---");		
      while(rsC.next()) {
    	  
        // Access and print contents of EACH tuple
        System.out.println (rsC.getInt (1) + "      " + rsC.getString (2) + "        " + rsC.getInt (3) + "         " + rsC.getDouble (4));
        
      }
      
      stmtC.close (); 
      
      
      // ***************************
      // D. Updating the INstructor Salaries Based on Merit LIst Query
      // ***************************
       // The mentors of the students in the MeritList computed in Part B need to be given salary raises.
      //The rules for raises are as follows:  Mentor of a senior gets 10% raise, mentor of a junior gets 8% raise, 
      //mentor of a sophomore gets 6% raise, and mentor of a freshman gets 4% raise. 
         
         
         
         //Section D.1:  Execute a query, receive result in a result set
   	  
   	    Statement stmtD = conn1.createStatement ();
   		      
        ResultSet rsD = stmtD.executeQuery ("SELECT m.MentorID, m.Classification"  + " " + 
                                             "FROM MeritList m" + " " + 
                                             "ORDER BY m.MentorID "); 
       
         
         double raise = 0;
         String classi;
         int mID;
         int mIDPrev = -1;
          
                		
         while(rsD.next()) {
       	   mID = rsD.getInt("MentorID"); 
       	   classi = rsD.getString("Classification");
           if (mID == mIDPrev) //check to see if classification of the current tuple is higher than any previous
           {
        	   
        	   //if it is we set raise to the newest one
        	   if (classi.equals("Freshman") && raise <= .04 ){
        		   
        		   raise = .04;
        	   }
        	   else if (classi.equals("Sophomore") && raise <= .06){
        		   
        		   raise = .06;
        	   }

        	   else if (classi.equals("Junior") && raise <= .08){
        		   
        		   raise = .08;
        	   }
        	   else if (classi.equals("Senior") && raise <= .1){
        		   raise = .10;
           		}
        	    
        	  // we have now updated a previously set raise for the currently visited mentorID if we found a higher one it was changed if we didn't, raise remains the same.
        	   
           } // end the if we have already visited and set a raise for this mentorID
           
           else  //we have a new mID so we set to tuple's classification's corresponding raise. We must also update table for last instructor.
           {
        	   
        	   if(mIDPrev != -1)//exempts the first tuple by not comparing his/her previous raise criteria and simultaneously not updating his salary in the database yet
        	   {
        		   
        	   //Since we have processed all of the tuples with the same mentorId we will update his/her salary with the appropriate raise, before we change the raise variable. 
        		   
        		   String updateStr =
        				    "update Instructor i" +
        				    " set i.Salary = i.Salary + (i.Salary * " + raise +")" +
        				    " where InstructorID = " + mIDPrev;
        		  
        		   
        		   
        				PreparedStatement updateStmt  = conn1.prepareStatement(updateStr);
        			
        				updateStmt.executeUpdate();
        				
        				updateStmt.close();
        	   
        	   }
        	   if (classi.equals("Freshman")){
        		   
        		   raise = .04;
        	   }
        	   else if (classi.equals("Sophomore")){
        		   
        		   raise = .06;
        	   }

        	   else if (classi.equals("Junior")){
        		   
        		   raise = .08;
        	   }
        	   else if (classi.equals("Senior")){
        		   raise = .10;
           		}
        	    
        	  //We know this was our first visit to any mentor with this id and now his/her raise has been set according to the first found student's classification.


           } // end else
           
           mIDPrev = mID;
           
        	   
        	   
           }//end while
         
         //handles the last instructors salary update. 
         
       //Since we have processed all of the tuples with the same mentorId we will update the final person's salary with the appropriate raise. 
		   
		   String updateFinalStr =
				    "update Instructor i" +
				    " set i.Salary = round(i.Salary + (i.Salary * " + raise +"))" +
				    " where InstructorID = " + mIDPrev;
		  
		   
		   
				PreparedStatement updateFinalStmt  = conn1.prepareStatement(updateFinalStr);
			
				updateFinalStmt.executeUpdate();
				
				updateFinalStmt.close();
         
				stmtD.close (); 
         
      
      //SECTION E: repeat step A
				
				  // Section E.2: Execute a query, receive result in a result set 
			      rs1 = stmt1.executeQuery ("select p.Name, i.Salary"  + " " + 
			                                          "from Person p, Instructor i" + " " + 
			                                          "where p.ID = i.InstructorID "); 

			      
			      //Section E.3:  Print a header for the report:
			      System.out.println ( );		
			      System.out.println ("Name           Salary");		
			      System.out.println ("----           ------");		

			      //Section A.4  Print report from the result set:

			      
			      totalPayroll = 0.0; 

			      iName = ""; // Reset value of Name attribute 
			      iSalary = 0; // Reset value of Salary attribute
			      while(rs1.next()) {
			         // Access and print contents of one tuple
			         iName = rs1.getString (1); // Value accessed by its position
			         iSalary = rs1.getInt ("Salary"); // Access by attribute Name
			         System.out.println (iName + "   " + iSalary);
			       
			         totalPayroll = totalPayroll + iSalary;
			      }			

			      //Section E.5 Print report for totalPayroll: 
			    
			      System.out.println ( );		
			      System.out.println ("Total payroll for Instructors: $" + totalPayroll);
			      System.out.println ( ); 

			      // Section E.6 Close statement 
			      stmt1.close (); 

      
      
      
      
      //SECTION F

      //drop the MeritList Table
      
      Statement stmtF = conn1.createStatement();
      
      String dropML = "DROP TABLE MeritList ";
 
      stmtF.executeUpdate(dropML);
      
      stmtF.close();

      // Section F.2: close connection
      
      conn1.close (); 
      System.out.println("the connection to the database has been closed.");
   } // End of try

   catch (SQLException E) {
      System.out.println ("SQLException: " + E.getMessage());
      System.out.println ("SQLState: " + E.getSQLState());
      System.out.println ("VendorError: " + E.getErrorCode());

   } // End of catch

} // end of main

} //end of class JDBC_2

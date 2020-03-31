<?php

#------------------------- Class file for mySQL Database -------------------------

# Written By Manjula Hewage
# Mail To : manjulahewage@yahoo.com

#---------------------------------------------------------------------------------

class mySqlDB
{
        var $dBaseKey;
		
        //Connect to mySQL Server
        //param1:mySQL server hostname:default-hostname
        //param2:Username:default-root
        //param3:Password:default-""
        function connect($hostName="localhost",$userName="",$passWord="")
        {			
         mysql_connect($hostName,$userName,$passWord) or die("Couldn't connect to mySQL server");
        }

        //Select the Database
        //param1:Database name:defualt-test
        function dBSelect($dBase="test")
        {
         $this->dBaseKey=mysql_select_db($dBase) or die("Couldn't select database");
        }

        //Closes the current opened database
        function dBClose()//Closes a database connection while it's open
        {
         //mysql_close($this->dBaseKey);
		  $conn = $this->dBaseKey;		
		   if(is_resource($conn))
			  mysql_close($conn);	
        }

        //Select Recordset
        //param1:Table Name
        //param2:Field name(s) to be selected as an array
        //param3:Fields name(s) to be sorted by as an array (optional)
        //param4:Sort By (A|D):default-A [A:Ascending,D:Descending](optional)
        //param5:Condition List:default=""(optional)
        //       syntax=>
        //       "field='value'"
        //       "field1='value1' AND field2='value2'"
        //       "field1='value1' OR field2='value2'"
        //param6:Limit Start
        //param7:No of records from the limit start
		 function asdf($tableName,$arrFields,$sort="",$sortBy="A",$arrCon="",$lStart=0,$numRecs=0)
        {
		
		}
		
        function querySelect($tableName,$arrFields,$sort="",$sortBy="A",$arrCon="",$lStart=0,$numRecs=0)
        {
         $fNames=implode(",",$arrFields);
         if(!$arrCon){
          $query="select $fNames from $tableName";
         }
         else{
          $query="select $fNames from $tableName where $arrCon";
         } 
		 
         if($sort){
          $strSort=implode(",",$sort);
          $query.=" order by $strSort";
          if($sortBy=="D")
          {
           $query.=" desc";
          }
         }
         if($numRecs)
         {
          $query.=" limit $lStart,$numRecs";
         }
		// echo '<br/> select sql :'.$query;
         $result=mysql_query($query);
         $a=0;
         if($result)
         {
          while($row=mysql_fetch_array($result)){
          for($i=0;$i<count($arrFields);$i++){
           $arrData[$a][$i]=$row[$i];
          }
          $a++;
          }
          return $arrData;
         }
         else
         {
          echo mysql_error();
         }
        }

        function queryUpdate($tblName,$insArr,$cond=false,$condStr="")
        {
         $arrInKeys=array_keys($insArr);
         $fNString=implode(",",$arrInKeys);
         $fValString="'".implode("','",$insArr)."'";
         if($cond)
         {
          $fUpString="";
          for($i=0;$i<count($arrInKeys);$i++)
          {
           $arrKeyId=$arrInKeys[$i];
           $fUpString.=$arrKeyId."='$insArr[$arrKeyId]'";
           if($i!=(count($arrInKeys)-1))
           {
            $fUpString.=",";
           }
          }
          if($condStr)
          {
           $sqlString="update $tblName set $fUpString where $condStr";
          }
          else
          {
           $sqlString="update $tblName set $fUpString";
          }
         }
         else
         {
          $sqlString="insert into $tblName ($fNString) values ($fValString)";
         }
		 
		// echo "<br/> update sql : $sqlString";
         $queryVal=mysql_query($sqlString);
         if(!$queryVal)
         {
          echo mysql_error();
         }
		 $newid=mysql_insert_id();
		 
		 return $newid;
        }

        function queryDelete($tblName,$condStr="")
        {
         if($condStr)
         {
          $sqlStr="delete from $tblName where $condStr";
         }
         else
         {
          $sqlStr="delete from $tblName";
         }
         $queryVal1=mysql_query($sqlStr);
         if(!$queryVal1)
         {
          echo mysql_error();
         }
        }
		
		function queryEmpty($tblName)
        {
         
         $sqlStr="TRUNCATE TABLE $tblName";
         
         $queryVal1=mysql_query($sqlStr);
         if(!$queryVal1)
         {
          echo mysql_error();
         }
        }
		
        function countNumRec($tableName,$field,$condStr=""){
                 if($condStr){
                      $sqlStr="select count($field) from $tableName where $condStr";
                 }
                 else{
                      $sqlStr="select count($field) from $tableName";
                 }
				 
                 $queryVal1=mysql_query($sqlStr);
                 if(!$queryVal1){
                     echo mysql_error();
                 }
                 else{
                     return mysql_result($queryVal1,0,0);
                 }
        }

        function sumOfField($tableName,$field,$condStr=""){
                 if($condStr){
                      $sqlStr="select sum($field) from $tableName where $condStr";
                 }
                 else{
                      $sqlStr="select sum($field) from $tableName";
                 }
                 $queryVal1=mysql_query($sqlStr);
                 if(!$queryVal1){
                     echo mysql_error();
                 }
                 else{
                     return mysql_result($queryVal1,0,0);
                 }
        }
		function getColumns($tableName,$arrFields){                 
			$query="SHOW COLUMNS FROM $tableName"; 			
			$result=mysql_query($query);
			$a=0;
			 if($result)
			 {
			  while($row=mysql_fetch_array($result)){
			  for($i=0;$i<count($arrFields);$i++){
			   $arrData[$a][$i]=$row[$i];
			  }
			  $a++;
			  }
			  return $arrData;
			 }
			 else
			 {
			  echo mysql_error();
			 }			
        }

}

?>
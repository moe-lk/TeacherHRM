<?php 

//This Functions Created by Duminda Wijewantha



function leadingZeros($num,$numDigits) {

   return sprintf("%0".$numDigits."d",$num);

}

/* $x = 3;

print sprintf("%04d",$x); */

function calculateNextAge($fromage,$toage){ //Y-m-d



	$fromdate=explode("-",$fromage);

	$from_year=$fromdate[0];

	$from_month=$fromdate[1];

	$from_date=$fromdate[2];

	

	$todate=explode("-",$toage);

	$to_year=$todate[0];

	$to_month=$todate[1];

	$to_date=$todate[2];

	

	$from_date = mktime(0, 0, 0, $from_month, $from_date, $from_year);

	$to_date = mktime(0, 0, 0, $to_month, $to_date, $to_year);

	$daysass = (($to_date - $from_date)/(60*60*24)) + 1;

	

	$noofyearsdouble=$daysass/365;

	$noofyears=round($noofyearsdouble,0);

	

	$year_deferance=$to_year-$from_year;

		  

	if($noofyearsdouble>$year_deferance){

		if($noofyears>$year_deferance){

			$age=$noofyears;

		}else $age=$noofyears+1;

		

	} else  if($noofyearsdouble<$year_deferance){

		if($noofyears<$year_deferance){

			$age=$noofyears+1;

		}else $age=$noofyears;

	}else $age=$year_deferance+1;

	

	return $age;

}



function calculateCurrentAge($fromage,$toage){ //Y-m-d



	$fromdate=explode("-",$fromage);

	$from_year=$fromdate[0];

	$from_month=$fromdate[1];

	$from_date=$fromdate[2];

	

	$todate=explode("-",$toage);

	$to_year=$todate[0];

	$to_month=$todate[1];

	$to_date=$todate[2];

	

	$from_date = mktime(0, 0, 0, $from_month, $from_date, $from_year);

	$to_date = mktime(0, 0, 0, $to_month, $to_date, $to_year);

	$daysass = (($to_date - $from_date)/(60*60*24)) + 1;

	

	$noofyearsdouble=$daysass/365;

	$noofyears=round($noofyearsdouble,0);



	$year_deferance=$to_year-$from_year;

		  

	if($noofyearsdouble>$year_deferance){

		$age= $year_deferance;

	} else  if($noofyearsdouble<$year_deferance){

		$age= $year_deferance-1;

	}

	

	return $age;

}



function validateDate($fromdate,$todate){ //Y-m-d



$fromdate=date("Y-m-d", strtotime($fromdate));

$todate=date("Y-m-d", strtotime($todate));



$fromdate=str_replace("-","",$fromdate);

$todate=str_replace("-","",$todate);



 $difference=$todate-$fromdate;



 if($difference<0){

	return 0;

}else return 1; 





}





?>
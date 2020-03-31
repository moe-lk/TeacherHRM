<?php 
//This Functions Created by Duminda Wijewantha

function leadingZeros($num,$numDigits) {
   return sprintf("%0".$numDigits."d",$num);
}
/* $x = 3;
print sprintf("%04d",$x); */

function initials($str) {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]).".";
    return $ret;
}


function leaveTypeAvailable($employeeID,$leaveType,$dLeaveFrom){
	include("config.php");
	$dbObj=new mySqlDB;
	$dbObj->connect($hostname,$username,$password);
	$dbObj->dBSelect($dbname);
	
	$tpe=$employeeID;
	$idLeType=$leaveType;
	$thisDYEar=date('Y');
	$thisDYEar=2015;
	if($dLeaveFrom!=''){
	   $expldeLv=explode("-",$dLeaveFrom);
	   $leavAppYear=$expldeLv[0];
	   $fromDateL=$leavAppYear."-01-01";
	   $toDateL=$leavAppYear."-12-31";
	}else{							   
		 $fromDateL=date('Y-01-01');
		 $toDateL=date('Y-12-31');
		 $fromDateL=date('2015-01-01');
		 $toDateL=date('2015-12-31');
	}
							
	
	   $selDataLeEnt=$selDataLeBrFor=$selDataLeTaken=$leaveBalance=0;
	   $selDataLeEnt=$dbObj->sumOfField("tbl_employee_leave_assign","dEntitleDays","iEmployeeID='$tpe' and iLeaveTypeID='$idLeType'");
	   $selDataLeBrFor=$dbObj->sumOfField("tbl_employee_leave_assign","dBroughtForDays","iEmployeeID='$tpe' and iLeaveTypeID='$idLeType'");
	   
	   $dRemainingDays=$dbObj->sumOfField("tbl_employee_leave_assign","dRemainingDays","iEmployeeID='$tpe' and iLeaveTypeID='$idLeType'");
	//   $assignLeave=$selDataLeEnt+$selDataLeBrFor;
	   //....
	$adjDatesLeav=0;
	$SDcatesLeaAdjestment=$dbObj->querySelect('tbl_employee_leave_adjestment',array("cAdjestmentOption","dNoofDays","id"),array("iLeaveTypeID"),"A","iEmployeeID='$tpe' and iLeaveTypeID='$idLeType' and dAdjestmentFrom like '$thisDYEar%'");
	$adjDatesLeav=0;
	
	for($aj=0;$aj<count($SDcatesLeaAdjestment);$aj++){
		   $cAdjestmentOption=$SDcatesLeaAdjestment[$aj][0];
		   $dNoofDaysAdj=$SDcatesLeaAdjestment[$aj][1];
		if($cAdjestmentOption=='P'){
			$adjDatesLeav+=$dNoofDaysAdj;
		}elseif($cAdjestmentOption=='M'){
			$adjDatesLeav-=$dNoofDaysAdj;
		}
	}
	
	$assignLeave=$selDataLeEnt+$adjDatesLeav;//+$selDataLeBrFor;//+$adjDatesLeav;
	   //....
	   
	  
	  
	   $selDataLeTaken=$dbObj->sumOfField("tbl_employee_leave_taken","dNoofDays","iEmployeeID='$tpe' and iLeaveTypeID='$idLeType' and cApproved='Y' and dLeaveFrom between '$fromDateL' and '$toDateL'");
	   
	   
	   $leaveBalance=$assignLeave-$selDataLeTaken;
	   return $leaveBalance;
}

function leaveTypeValue($DateapyL,$TypeID) {	
	include("config.php");
	$dbObj=new mySqlDB;
	$dbObj->connect($hostname,$username,$password);
	$dbObj->dBSelect($dbname);
	//dMon`, `dTue`, `dWed`, `dThu`, `dFri`, `dSat`, `dSun
	$sealField=array("dMon","dTue","dWed","dThu","dFri","dSat","dSun");
	$fiedLable=$dbObj->querySelect("tbl_company_days_working_category",$sealField,array("id"),"A","id='$TypeID'");
	$dMon = $fiedLable[0][0];
	$dTue = $fiedLable[0][1];
	$dWed = $fiedLable[0][2];
	$dThu = $fiedLable[0][3];
	$dFri = $fiedLable[0][4];
	$dSat = $fiedLable[0][5];
	$dSun = $fiedLable[0][6];
	$returnVal="";
	if($DateapyL=='Mon')$returnVal=$dMon;
	if($DateapyL=='Tue')$returnVal=$dTue;
	if($DateapyL=='Wed')$returnVal=$dWed;
	if($DateapyL=='Thu')$returnVal=$dThu;
	if($DateapyL=='Fri')$returnVal=$dFri;
	if($DateapyL=='Sat')$returnVal=$dSat;
	if($DateapyL=='Sun')$returnVal=$dSun;
	return $returnVal;
}


function checkEndDate($dNoofDays,$fromDate,$leaveCatID,$cHalfDayStart){
	include("config.php");
	$dbObj=new mySqlDB;
	$dbObj->connect($hostname,$username,$password);
	$dbObj->dBSelect($dbname);
	
	$succesDays="";
	$t=0;
	if($cHalfDayStart=='B'){
		$t=1;
		$dNoofDays=$dNoofDays+0.5;
	}
	for($p=$t;$p<$dNoofDays;$p++){
	  //echo $p;
	$dLeaveToDateGuess = strtotime(date("Y-m-d", strtotime($fromDate)) . " +$p day");
	$dLeaveToDateGuess=date("Y-m-d",$dLeaveToDateGuess);
	$DateapyL=date("D", strtotime($dLeaveToDateGuess));//echo "<br>";
	$succDateOf="";
	
	$selDataCal=$dbObj->querySelect("tbl_company_days_holidays",array("dHolidayType"),array("id"),"A","dDate='$dLeaveToDateGuess'");
	$holidayDays=$selDataCal[0][0];
	  
	 if(count($selDataCal)==0){
		 $typeVal=leaveTypeValue($DateapyL,$leaveCatID);//echo "<br>";
		if($typeVal==0){
			$dNoofDays=$dNoofDays+1;
		}else if($typeVal==0.5){
			$dNoofDays=$dNoofDays+0.5;
			$succesDays=$succesDays+0.5;
			$succDateOf=$dLeaveToDateGuess;
			$LeaveDayType=0.5;
		}else{
			$succesDays=$succesDays+1;
			if($succesDays-$actualDays==0.5){
				$LeaveDayType=0.5;
			}else{
				$LeaveDayType=1;
			}
			$succDateOf=$dLeaveToDateGuess;
		}
	 }else {
		 if($holidayDays=='0.5'){
			 $dNoofDays=$dNoofDays+0.5;
			 $succesDays=$succesDays+0.5;
			 $LeaveDayType=0.5;
			 $succDateOf=$dLeaveToDateGuess;
		  }else{
				 $dNoofDays=$dNoofDays+1;//$holidayDays==1;  exit();
		 }
	 }
	 //echo $succDateOf;echo "<br>";
	}
	return $leaveTodate=$succDateOf;	
}

function selectRedirectOption($pageid){
	include("config.php");
	$dbObj=new mySqlDB;
	$dbObj->connect($hostname,$username,$password);
	$dbObj->dBSelect($dbname);
	$SD=$dbObj->querySelect("tbl_redirect_option",array("id","cOption"),array("id"),"A","iPageID='$pageid'");
	return $cOption=$SD[0][1];
}

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

function calculateMonthInService($fromage,$toage){ //Y-m-d

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
	
	/* $noofmonthsdouble=$daysass/30;
	$noofyears=round($noofmonthsdouble,0);

	$year_deferance=$to_year-$from_year;
		  
	if($noofyearsdouble>$year_deferance){
		$age= $year_deferance;
	} else  if($noofyearsdouble<$year_deferance){
		$age= $year_deferance-1;
	} */
	
	return $daysass;
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
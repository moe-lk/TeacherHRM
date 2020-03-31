<?php 
function getAddress() {
    /*** check for https ***/
    $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
    /*** return the full address ***/
    return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 }
 



function add_logs($userid,$page,$des) {   
    $mip=$_SERVER['REMOTE_ADDR'];
    $dte=date('Y-m-d H:i:s');
	$cEnable="Y";
    include("config.php");
    $connect = mysql_connect($hostname,$username,$password) or die ("Error: could not connect to database");
    $db = mysql_select_db($dbname);
    $rsql="insert into tbl_logs (vPage,iUserID,tDes,dDateTime,vIP,cEnable) values ('$page',$userid,'$des','$dte','$mip','$cEnable')";
    $result = mysql_query($rsql) or die ('test');
}
//calculate incentive for each day
function calculate_incentive($items) {	
	$dte=date('Y-m-d H:i:s');
	include("config.php");
	//include("include/class_db.php");
	$dbObj=new mySqlDB;
	$dbObj->connect($hostname,$username,$password);
	$dbObj->dBSelect($dbname);
	
	$today=date('Y-m-d');
	$dayold=date('Y-m-d',mktime(0, 0, 0, date('m'), date('d')-7,  date('Y')));
	for($i=0;$i<90;$i++){ //for last 30 days
		$day_from=date('Y-m-d',mktime(0, 0, 0, date('m'), date('d')-$i,  date('Y')))." 00:00:00";
		$day_to=date('Y-m-d',mktime(0, 0, 0, date('m'), date('d')-$i,  date('Y')))." 23:59:59";
		$day_common=date('Y-m-d',mktime(0, 0, 0, date('m'), date('d')-$i,  date('Y')));
				
		$SD=$dbObj->querySelect("tbl_daily_operations",array("id","dDateTime","iNoOfEmp","dLineEfficiency","dWorkingHours","dQty","dSMV","dPaymentperOperator"),array("id"),"A","cEnable='Y' and dDateTime between '$day_from' and '$day_to' and vError=''");
		$tot_eff=0;
		$op_ids="";	
		$emp_ids="";	
		$smvqty=0;
		$whop=0;
		$pmet=0;
		if(count($SD)>0) {
			for($x=0;$x<count($SD);$x++){        
				$id=$SD[$x][0];
				$dDateTime=$SD[$x][1];
				$iNoOfEmp=$SD[$x][2];
				$dLineEfficiency=$SD[$x][3];
				$dPaymentperOperator=$SD[$x][7];
				$tot_eff=$tot_eff+$dLineEfficiency;
				if($op_ids=='') { $op_ids.=$id; } else { $op_ids.=",".$id; }
				$smvqty=$smvqty+($SD[$x][6]*$SD[$x][5]);
				$whop=$whop+($SD[$x][4]*$SD[$x][2]);
				$pmet=$pmet+($SD[$x][7]*$SD[$x][4]);
			}
			$eff=0;
			$eff=$smvqty/($whop*3600);
			$dIncentiveLevel=round($eff,2);		
			$amount=$pmet/8.5;	
					
			//get the employee ids
			$sd2=$dbObj->querySelect("tbl_daily_operations_employees",$fieldNames=array("iEmpID"),array("iEmpID"),"A","iDailyOperationID in ($op_ids)");
			for($n=0;$n<count($sd2);$n++){     
				if($emp_ids=='') { $emp_ids.=$sd2[$n][0]; } else { $emp_ids.=",".$sd2[$n][0]; }	
			}
			
			$emp_ids1=explode(',',$emp_ids);
			$emp_ids1=array_unique($emp_ids1);
			$emp_ids2=implode(',',$emp_ids1);					
			//
			$insArrCusE=array();
			$insArrCusE["dDate"]=$day_common;		  
			$insArrCusE["dTotalEfficiency"]=round($eff*100,2);
			$insArrCusE["vOperationIDs"]=",".$op_ids.",";
			$insArrCusE["dPayment"]=$amount;
			$insArrCusE["tEmpID"]=",".$emp_ids2.",";			
			if($dbObj->countNumRec("tbl_payments","id","dDate='$day_common'")>0) {
				$dbObj->queryUpdate("tbl_payments",$insArrCusE,true,"dDate='$day_common'");
			} else {
				$id=$dbObj->queryUpdate("tbl_payments",$insArrCusE);
			}
		}//end of count($SD)		
	}//end for last 30 days
}
?>
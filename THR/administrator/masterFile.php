<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<style type="text/css">

/* by duminda 2015-10-07 left menu */
 .menuItemSelected{ 
	float:left; 
	width:194px;
	padding:2px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:12px;
	line-height:24px;
	color:#FFF;
	border-radius: 2px;
	background-color:#900;
	
	}
	.menuItem{ 
	float:left; 
	width:194px;
	padding:2px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:12px;
	line-height:24px;
	color:#FFF;
	border-radius: 2px;
	background-color:#A4B6FF;
	
	}
</style>


    <div class="masterFile">
         <!--<h3><a href="http://localhost/EMIS/">Section 1</a></h3>
    
         <div class="pane">
            <div style="float:left; width:100px; background-color:#999;">test</div>
        </div>
        
         <h3>Section 2</h3>
    
        <div class="pane">
            <ul>
                <li>Censes</li>
                <li>Civil Status</li>
                <li>District</li>
            </ul>
        </div>-->
         <h3>Master Files</h3>
    
        <div class="pane">
            <table width="100%" cellspacing="1" cellpadding="1">
                
              
              <tr>
                <td <?php if($menu==22){?>class="menuItem"<?php }?>><a href="masterAccessRoles-11-22.html">Access Role</a></td>
              </tr>
              <tr>
                <td <?php if($menu==1){?>class="menuItem"<?php }?>><a href="masterSences-11-1.html">Schools/Institutions</a></td>
              </tr>
              <tr>
                <td <?php if($menu==2){?>class="menuItem"<?php }?>><a href="masterCivilStatus-11-2.html">Civil Status</a></td>
              </tr>
              <tr>
                <td <?php if($menu==3){?>class="menuItem"<?php }?>><a href="masterDistrict-11-3.html">District</a></td>
              </tr>
              <tr>
                <td <?php if($menu==4){?>class="menuItem"<?php }?>><a href="masterDivision-11-4.html">Division</a></td>
              </tr>
              <tr>
                <td <?php if($menu==26){?>class="menuItem"<?php }?>><a href="masterDsDivision-11-26.html">DS Division</a></td>
              </tr>
              <tr>
                <td <?php if($menu==5){?>class="menuItem"<?php }?>><a href="masterEmployeeType-11-5.html">Employee Type</a></td>
              </tr>
              <tr>
                <td <?php if($menu==6){?>class="menuItem"<?php }?>><a href="masterEthnicity-11-6.html">Ethnicity</a></td>
              </tr>
              <tr>
                <td <?php if($menu==7){?>class="menuItem"<?php }?>><a href="masterGender-11-7.html">Gender</a></td>
              </tr>
               <tr>
                <td <?php if($menu==25){?>class="menuItem"<?php }?>><a href="masterIncrementQuections-11-25.html">Increment Questions</a></td>
              </tr>
              <tr>
                <td <?php if($menu==8){?>class="menuItem"<?php }?>><a href="masterLeaveType-11-8.html">Leave Type</a></td>
              </tr>
              <tr>
                <td <?php if($menu==9){?>class="menuItem"<?php }?>><a href="masterMedium-11-9.html">Medium</a></td>
              </tr>
              <tr>
                <td <?php if($menu==10){?>class="menuItem"<?php }?>><a href="masterPosition-11-10.html">Position</a></td>
              </tr>
              <tr>
                <td <?php if($menu==11){?>class="menuItem"<?php }?>><a href="masterProvince-11-11.html">Province</a></td>
              </tr>
              <tr>
                <td <?php if($menu==12){?>class="menuItem"<?php }?>><a href="masterQualiCategory-11-12.html">Qualification Category</a></td>
              </tr>
              <tr>
                <td <?php if($menu==13){?>class="menuItem"<?php }?>><a href="masterQualification-11-13.html">Qualification</a></td>
              </tr>
              <tr>
                <td <?php if($menu==14){?>class="menuItem"<?php }?>><a href="masterReligion-11-14.html">Religion</a></td>
              </tr>
              <tr>
                <td <?php if($menu==24){?>class="menuItem"<?php }?>><a href="masterSalaryScale-11-24.html">Salary Scale</a></td>
              </tr>
              <tr>
                <td <?php if($menu==15){?>class="menuItem"<?php }?>><a href="masterSection-11-15.html">Section</a></td>
              </tr>
              <tr>
                <td <?php if($menu==16){?>class="menuItem"<?php }?>><a href="masterService-11-16.html">Service</a></td>
              </tr>
              <tr>
                <td <?php if($menu==17){?>class="menuItem"<?php }?>><a href="masterServiceRecordType-11-17.html">Service Record Type</a></td>
              </tr>
              <tr>
                <td <?php if($menu==18){?>class="menuItem"<?php }?>><a href="masterSubject-11-18.html">Subject</a></td>
              </tr>
              <tr>
                <td <?php if($menu==19){?>class="menuItem"<?php }?>><a href="masterSubjectType-11-19.html">Subject Type</a></td>
              </tr>
              <tr>
                <td <?php if($menu==20){?>class="menuItem"<?php }?>><a href="masterTitle-11-20.html">Title</a></td>
              </tr>
              <tr>
                <td <?php if($menu==21){?>class="menuItem"<?php }?>><a href="masterWorkStatus-11-21.html">Work Status</a></td>
              </tr>
              <tr>
                <td <?php if($menu==23){?>class="menuItem"<?php }?>><a href="masterZone-11-23.html">Zone</a></td>
              </tr>
              <tr>
                <td <?php if($menu==27){?>class="menuItem"<?php }?>><a href="master2016Circular-11-27.html">01/2016 circular</a></td>
              </tr>
              
            </table>
    
        </div>
    </div>
<div class="main_content_inner_block" style="width:720px; height:auto; float:left; margin-left:10px; border:thick; border-color:#666; border-width:1px; border-style:solid; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding-left:5px; padding-right:5px;">
            
        <?php 
		if($menu==1 || $menu=='')include("masterSences.php");
		if($menu==2)include("masterCivilStatus.php");
		if($menu==3)include("masterDistrict.php");
		if($menu==4)include("masterDivision.php");
		if($menu==5)include("masterEmployeeType.php");
		if($menu==6)include("masterEthnicity.php");
		if($menu==7)include("masterGender.php");
		if($menu==8)include("masterLeaveType.php");
		if($menu==9)include("masterMedium.php");
		if($menu==10)include("masterPosition.php");
		if($menu==11)include("masterProvince.php");
		if($menu==12)include("masterQualiCategory.php");
		if($menu==13)include("masterQualification.php");
		if($menu==14)include("masterReligion.php");
		if($menu==15)include("masterSection.php");
		if($menu==16)include("masterService.php");
		if($menu==17)include("masterServiceRecordType.php");
		if($menu==18)include("masterSubject.php");
		if($menu==19)include("masterSubjectType.php");
		if($menu==20)include("masterTitle.php");
		if($menu==21)include("masterWorkStatus.php");
		if($menu==22)include("masterAccessRoles.php");
		if($menu==23)include("masterZone.php");
		if($menu==24)include("masterSalaryScale.php");
		if($menu==25)include("masterIncrementQuections.php");
		if($menu==26)include("masterDsDivision.php");
		if($menu==27)include("master2016Circular.php");
		?>
   
</div>
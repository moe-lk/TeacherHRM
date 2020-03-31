<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<link type='text/css' href='../assets/css/dashboard.css' rel='stylesheet' media='screen'/>
<?php 
$msg="";
$tblNam="TG_ContactUs";
$countTotal="SELECT * FROM $tblNam where NIC='$NICUser'";

if(isset($_POST["FrmSubmit"])){	

	//echo "hi"; NIC, SurnameWithInitials, MobileTel, ,InqType, InqDescription      
	$NIC=$_REQUEST['NIC'];
	$SurnameWithInitials=$_REQUEST['SurnameWithInitials'];
	$CenCode=$_REQUEST['CenCode'];
	$MobileTel=$_REQUEST['MobileTel'];
	$InqType=$_REQUEST['InqType'];
	$InqDescription=addslashes($_REQUEST['InqDescription']);
	$dDateTime=date('Y-m-d H:i:s');
	
	$sqlServiceRef=" SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NIC')";
	$stmtCAllready= $db->runMsSqlQuery($sqlServiceRef);
	$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
	$CurServiceRef=trim($rowAllready['CurServiceRef']);
	$ZoneCode=trim($rowAllready['ZoneCode']);
	

	$RecordLog="Initial Record";
	
	if($InqDescription!=''){
		$queryGradeSave="INSERT INTO TG_Employee_Inquiry
			   (NIC,SurnameWithInitials,CenCode,MobileTel,InqType,InqDescription,dDateTime,RecordLog,IsAnswered,Answer,AnsweredDate,AnswerBy,ZoneCode)
		 VALUES
			   ('$NIC','$SurnameWithInitials','$CenCode','$MobileTel','$InqType','$InqDescription','$dDateTime','$RecordLog','N','','$AnsweredDate','$AnswerBy','$ZoneCode')";
			   
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			$msg="Successfully Updated.";
	}else{
		$msg="Please enter the Inquiry Description.";
	}
	//sqlsrv_query($queryGradeSave);
}
	
?>


<div class="main_content_inner_block">
    <form method="post" action="contactUsSendEmail.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php //$_SESSION['SuccessContact']="Thank you for contacting with us. We will revert to you soon.";
		if($_SESSION['success_update']!='' || $_SESSION['SuccessContact']!=''){  ?>   
   	  <div class="mcib_middle1">
        <div class="mcib_middle_full">
          <div class="form_error" style="margin-top:10px; padding-bottom:10px"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['SuccessContact'];$_SESSION['SuccessContact']="";?></div>
        </div>
        <?php }?>
        <table width="100%" cellpadding="0" cellspacing="0">
       <?php  //if($menu!='' || $id!=''){?>
			 
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top"align="center"></td>
			    <td valign="top"></td>
			    <td valign="top">&nbsp;</td>
	      </tr>
			  <tr>
                  <td width="49%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                   
                    <tr>
                      <td align="left" valign="middle">Name<span class="form_error"> *</span></td>
                      <td valign="middle">:</td>
                      <td><input name="name" type="text" class="input2" id="name" value=""/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="middle">Contact Number<span class="form_error"> *</span></td>
                      <td valign="middle">:</td>
                      <td><input name="telephone" type="text" class="input2" id="telephone" value=""/></td>
                    </tr>
                    
                    <tr>
                      <td align="left" valign="middle">Email Address <span class="form_error">*</span></td>
                      <td align="left" valign="middle">:</td>
                      <td><input name="email" type="text" class="input2" id="email" value=""/></td>
                    </tr>
                     <tr>
                      <td align="left" valign="middle">Subject<span class="form_error"> *</span></td>
                      <td valign="middle">:</td>
                      <td><input name="subject" type="text" class="input2" id="subject" value=""/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Message<span class="form_error"> *</span></td>
                      <td align="left" valign="top">:</td>
                      <td><textarea name="inquiries" cols="45" rows="5" class="textarea1b" id="inquiries" tabindex="10"></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Human Verification<span class="form_error"> *</span></td>
                      <td align="left" valign="top">:</td>
                      <td>
                      <div class="captch_div">
          <!-- chr secret key 6LecDBkTAAAAAKdc5gFp2Pm7YGZpxF-YPJ7G5ZWR -->
          		<div class="g-recaptcha en" data-sitekey="6LdBahAUAAAAAIzU8b7HgP1wRRu9OPfaSfJdcC_m" style="transform:scale(0.77);transform-origin:0;-webkit-transform:scale(0.77);transform:scale(0.77);-webkit-transform-origin:0 0;transform-origin:0 0;"></div>
         		<div class="contact_data" style="color:#F00; margin-left:0px; display:none;" id="div_cpterror">* Please Complete Captcha</div>
          
          	</div>
                     </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="button" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" onClick="submit_form()"/></td>
                    </tr>
                    
                    </table>
        </td>
                <td width="1%" valign="top"align="center"></td>
                  <td width="1%" valign="top" style="border-left: 1px solid #999; padding-left:10px;" align="center"></td>
        <td width="49%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
          <tr>
            <td width="5%"><img src="../images/address.png" alt="Address" title="Address" width="20" height="14" /></td>
            <td width="95%">NEMIS Team</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Data Management Branch</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Ministry Of Education</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>"Isurupaya"</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Pelawatta</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Battaramulla</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td> Sri Lanka</td>
          </tr>
          <tr>
            <td><img src="../images/email.png" alt="email" title="E-mail address" width="15" height="16" /></td>
            <td valign="middle">nemis@moe.gov.lk</td>
          </tr>
          <tr>
            <td><img src="../images/phone.png" alt="phone" title="Phone number" width="16" height="16" /></td>
            <td valign="middle">+94 112075854</td>
          </tr>
          <tr>
            <td><img src="../images/fax.png" alt="fax" title="Fax number" width="16" height="16" /></td>
            <td valign="middle">+94 112075854</td>
          </tr>
        </table></td>
          </tr>
          
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
          <?php //}?>
         
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
    </div>
    
    </form>
</div>
<script>
          function submit_form()
          {

              var error;

              var fName = $('#name').val();
              var fName1 = document.getElementById("name");

              var telephone = $('#telephone').val();
              var telephone1 = document.getElementById("telephone");

              var email = $('#email').val();
              var email1 = document.getElementById("email");
              var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
              var alphaExpChar = /^[a-zA-Z\s-, ]+$/;
              var alphaExpNum = /^[0-9]+$/;

              var message = $('#inquiries').val();
              var message1 = document.getElementById("inquiries");
			  
			  var subject = $('#subject').val();
              var subject1 = document.getElementById("subject");
            

              var googleResponse = document.getElementById('g-recaptcha-response').value;
              if (googleResponse == '')
              {

                  error = true;
                  document.getElementById('div_cpterror').style.display = "block";
              }
              else
              {
                  document.getElementById('div_cpterror').style.display = "none";
              }

              if (fName == '' || fName.length < 1 || !fName.match(alphaExpChar) || fName == 'null' || fName.trim() == '') {

                  $('#name').attr('class', 'input2_error_b');
                  fName1.placeholder = "Please Enter Your Name";
                  error = true;
              }
             if (telephone == '' || telephone.length < 9 || telephone.length > 20) {
                  $('#telephone').attr('class', 'input2_error_b');
                  telephone1.placeholder = "Please Enter Your Telephone";
                  error = true;
              }
              if (email.trim() != "" && email.match(emailExp)) {
              } else {
                  $('#email').attr('class', 'input2_error_b');
                  email1.placeholder = "Please Enter Email Address";
                  error = true;
              }
              if (message == '') {
                  $('#inquiries').attr('class', 'textarea1b_error');
                  message1.placeholder = "Please Enter Your Message";
                  error = true;
              }
              if (subject == '') {
                  $('#subject').attr('class', 'input2_error_b');
                  subject1.placeholder = "Please Enter The Subject";
                  error = true;
              }

              if (error == true) {
                  //Recaptcha.reload();

                  return false;
              } else {//alert('hi');
                  //document.getElementById('telephone_1').value=telephone;
                  //document.getElementById('inquiries_1').value=message;
                  //document.getElementById('address_1').value=address;
                  document.forms["frmSave"].submit();
              }

          }
		  
		   

          $(function() {

              $("#name").click(function() {
                  $('#name').attr('class', 'input2');
              });


             $("#telephone").click(function() {
                  $('#telephone').attr('class', 'input2');
              });

              $("#email").click(function() {
                  $('#email').attr('class', 'input2');
              });

              $("#inquiries").click(function() {
                  $('#inquiries').attr('class', 'textarea1b');
              });
              $("#subject").click(function() {
                  $('#subject').attr('class', 'input2');
              });
          });

          function numericFilter(txb) {
              txb.value = txb.value.replace(/[^\0-9\ ]/ig, "");
              if (txb.value.match(/\s/g)) {
//alert('Sorry, you are not allowed to enter any spaces');
                  txb.value = txb.value.replace(/\s/g, '');
              }
          }
</script> 
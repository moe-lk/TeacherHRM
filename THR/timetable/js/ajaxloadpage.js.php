<script type="text/javascript">
function checkGroupSubxx(totalRows,subjCode){
	alert(subjCode);
	checkOverload(totalRows);
}
function checkGroupSub(totalRows,subjCode,SchoolID,GradeID,ClassID,valRaw,FieldID,PeriodNumberM,subjCodeFirst,dayday)
{
	/* var iVehicleTypeID=Number($('#iVehicleTypeID').val());
	var iUsageID=Number($('#iUsageID').val());
	var iTypeOfScheme=Number($('#iTypeOfScheme').val());
	var iratesThirdParyAmtXX=Number($('#iratesThirdParyAmtXX').val()); */
	var divVal="txt_group_TT_"+valRaw;
	//alert(divVal);
	//var dayday="Monday";
	
	if(SchoolID && GradeID && ClassID){
	 var linksend="ajaxCall/FilterDB.php?RequestType=loadGroupSubject&SchoolID="+SchoolID+"&GradeID="+GradeID+"&ClassID="+ClassID+"&subjCode="+subjCode+"&totalRows="+totalRows;
	// alert(linksend);
		$('#txt_group_TT_'+valRaw).load(linksend, function() {
			//alert(linksend);
		});	 
	}
	
	checkOverload(totalRows);
	//alert(FieldID);
	//checkTeacherAvailability(SchoolID,GradeID,ClassID,FieldID,PeriodNumberM,dayday,subjCode)
}

function checkTeacherAvailability(SchoolID,GradeID,ClassID,fieldIDTT,periodID,dayTT,subjCode){
	//alert(subjCode);//alert(dayTT);
	
	//showPleaseWait();
	var TeacherID;
	 var linksendValueN="ajaxCall/FilterDB.php?RequestType=getTeacherNICTT&GradeID="+GradeID+"&SchoolID="+SchoolID+"&ClassID="+ClassID+"&subjCode="+subjCode+"&resultAsJson=1"; 
	 
	  $.ajax({
            type: "GET",
            url: linksendValueN,
            dataType: 'json',
            success: function(data, msg)
            {	
				TeacherID= data.teacherID;
				//alert(TeacherID);
				
				var linksendValue="ajaxCall/FilterDB.php?RequestType=checkTeacherAvailability&GradeID="+GradeID+"&SchoolID="+SchoolID+"&ClassID="+ClassID+"&fieldIDTT="+fieldIDTT+"&TeacherID="+TeacherID+"&periodID="+periodID+"&dayTT="+dayTT+"&subjCode="+subjCode+"&resultAsJson=1"; 
	 //alert(linksendValue);
	   			$.ajax({
            type: "GET",
            url: linksendValue,
            dataType: 'json',
            success: function(data, msg)
            {	
				var FieldIDTT=data.FieldIDTT;
				//alert(FieldIDTT);
				//alert(data.totalRows);
				
				if(data.StatusTT=='Err'){
					$("#"+FieldIDTT).attr('class', 'select2a_red_teacher');
				}else if(data.StatusTT=='Suc'){
					$("#"+FieldIDTT).attr('class', 'select2a_green');
				}
				
				
								
			}
	   })
	   
                   
			}
	   })
	 
	 
	//showPleaseWait();
	//var TeacherID = $("#"+fieldIDTT).val();
	//setTimeout(function(){},5000);
	/* setTimeout(function(){
	
	 
	   
	   },5000); */
	   hidePleaseWait();
}

function checkTeacherAvailability_old(SchoolID,GradeID,ClassID,fieldIDTT,periodID,dayTT,subjCode){
	//alert(subjCode);//alert(dayTT);
	
	//showPleaseWait();
	var TeacherID;
	 var linksendValueN="ajaxCall/FilterDB.php?RequestType=getTeacherNICTT&GradeID="+GradeID+"&SchoolID="+SchoolID+"&ClassID="+ClassID+"&subjCode="+subjCode+"&resultAsJson=1"; 
	 
	  $.ajax({
            type: "GET",
            url: linksendValueN,
            dataType: 'json',
            success: function(data, msg)
            {	
				TeacherID= data.teacherID;
				
				var linksendValue="ajaxCall/FilterDB.php?RequestType=checkTeacherAvailability&GradeID="+GradeID+"&SchoolID="+SchoolID+"&ClassID="+ClassID+"&fieldIDTT="+fieldIDTT+"&TeacherID="+TeacherID+"&periodID="+periodID+"&dayTT="+dayTT+"&subjCode="+subjCode+"&resultAsJson=1"; 
	 //alert(linksendValue);
	   			$.ajax({
            type: "GET",
            url: linksendValue,
            dataType: 'json',
            success: function(data, msg)
            {	
				var FieldIDTT=data.FieldIDTT;
				//alert(data.totalRows);
				//alert(data.StatusTT);
				if(data.StatusTT=='Err'){
					$("#"+FieldIDTT).attr('class', 'select2a_red');
				}else if(data.StatusTT=='Suc'){
					$("#"+FieldIDTT).attr('class', 'select2a_green');
				}
				
				var successTeachr= data.teacherID;
                   
				var successTT= data.successTT;
                   // alert(successTT);
                    //var overLMT = ',1,2,3,4,5,6,';
                    var temp1 = new Array();
                    // this will return an array with strings "1", "2", etc.
                    temp1 = successTT.split(",");
                    
				 if(data.successTT!=''){ 
					for (a in temp1 ) {
						var underld=temp1[a];
						//alert(underld);
						if(underld!=''){
							$("#"+underld).attr('class', 'select2a_green');
							$("#"+underld).val(successTeachr);
							/* for(i=1;i<totalRows+1;i++){
								var fieldVal="TT"+i;
								var valTT=$("#"+fieldVal).val();

								if(underld==valTT){
										//alert(data.overLMT);
										$("#TT"+i).attr('class', 'select2a_green');
								}
							} */
						}
					}
				 }
				 
				 var failTT= data.failTT;
                   // alert(failTT);
                    //var overLMT = ',1,2,3,4,5,6,';
                    var temp2 = new Array();
                    // this will return an array with strings "1", "2", etc.
                    temp2 = failTT.split(",");
                    
				 if(data.failTT!=''){ 
					for (b in temp2 ) {
						var underld=temp2[b];
						//alert(underld);
						if(underld!=''){
							$("#"+underld).attr('class', 'select2a_red');
							$("#"+underld).val(successTeachr);
							/* for(i=1;i<totalRows+1;i++){
								var fieldVal="TT"+i;
								var valTT=$("#"+fieldVal).val();

								if(underld==valTT){
										//alert(data.overLMT);
										$("#TT"+i).attr('class', 'select2a_green');
								}
							} */
						}
					}
				 }
								
			}
	   })
	   
                   
			}
	   })
	 
	showPleaseWait();
	//var TeacherID = $("#"+fieldIDTT).val();
	//setTimeout(function(){},5000);
	/* setTimeout(function(){
	
	 
	   
	   },5000); */
	   hidePleaseWait();
}
function checkOverload(totalRows){
    showPleaseWait();
    var subArr=$("#subArr").val();
    var TT1;
	//alert('hi');
    
    //TT1.style.backgroundColor = 'red';
   // print(subArr);
    //print_r(subArr);
   //alert(select);
    //alert('hi');
   // $("#TT2").attr('class', 'select2a_green');
  //  $("#TT3").attr('class', 'select2a_red');
    
    var GradeID = $("#GradeID").val();
    var SchoolID = $("#SchoolID").val();
	var ClassID = $("#ClassID").val();
    var i;
    var subjSel;
    var formArry=[];
    //alert(formArry);
    for(i=1;i<totalRows+1;i++){
       var fieldVal="TT"+i;
       var valTT=$("#"+fieldVal).val();
	   $("#TT"+i).attr('class', 'select2a_new');
        formArry.push(valTT);
       
    }
    //alert(formArry);
    var linksendValue="ajaxCall/FilterDB.php?RequestType=checkOverLoad&GradeID="+GradeID+"&SchoolID="+SchoolID+"&ClassID="+ClassID+"&totalRows="+totalRows+"&currentTT="+formArry+"&resultAsJson=1";   
  // alert(linksendValue);
    $.ajax({
            type: "GET",
            url: linksendValue,
            dataType: 'json',
            success: function(data, msg)
            {//alert(data.mrupfrontproceed);
                var totalRowsRes=data.totalRows;
                //var subArr=$("#TT1").val();
              // alert(totalRowsRes);
                var i;
                //$("#TT1").attr('class', 'select2a_red');
                var overLMT= data.overLMT;
                //alert(overLMT);
                //var overLMT = ',1,2,3,4,5,6,';
                var temp = new Array();
                // this will return an array with strings "1", "2", etc.
                temp = overLMT.split(",");
//alert('tmp');
//alert(temp);
//alert('tmpend');
//for (a in temp ) {
    //temp[a];// = parseInt(temp[a], 10); // Explicitly include base as per Álvaro's comment
   // alert(temp[a]);
//}
				if(data.overLMT!=''){ 
					for (a in temp ) {
						var overld=temp[a];
						if(overld!=''){
							for(i=1;i<totalRows+1;i++){
									var fieldVal="TT"+i;
									//alert(fieldVal);
									var valTT=$("#"+fieldVal).val();

									if(overld==valTT){
											//alert(data.overLMT);
											$("#TT"+i).attr('class', 'select2a_red');
									}

							}
						}
					}
				}
				
                    var underLMT= data.underLMT;
                   // alert(underLMT);
                    //var overLMT = ',1,2,3,4,5,6,';
                    var temp2 = new Array();
                    // this will return an array with strings "1", "2", etc.
                    temp2 = underLMT.split(",");
                    
				 if(data.underLMT!=''){ 
					for (b in temp2 ) {
						var underld=temp2[b];
						if(underld!=''){
							for(i=1;i<totalRows+1;i++){
									var fieldVal="TT"+i;
									var valTT=$("#"+fieldVal).val();

									if(underld==valTT){
											//alert(data.overLMT);
											$("#TT"+i).attr('class', 'select2a_green');
									}


							}
						}
					}
				 }
				 
                 $('#test').val(data.overLMT);     
                    /*if(data.overLMT!=''){ 	//alert(data.dUpFrontNCB);
                        var OverLmtData=data.overLMT;
                        alert(OverLmtData);                        
                        var OverLmtDataSplt=OverLmtData.split(',');
                        alert(OverLmtDataSplt);
                       // if(OverLmtData.inArray(subArr)){
                            $("#TT1").attr('class', 'select2a_red');
                        
                      //  }
                            $('#test').val(OverLmtDataSplt);                     
                            
                            //setTimeout("calculateUpFrontNCB();",600);
                            //calculateUpFrontNCB();
                     }*/
                     if(data.underLMT!=''){ 	//alert(data.dUpFrontNCB);
                            $('#test2').val(data.underLMT);                     
                            
                            //setTimeout("calculateUpFrontNCB();",600);
                            //calculateUpFrontNCB();
                     }

              //calculateSubTotal();
            }
	});
    
    /*$.ajax({
     
        url: "ajaxCall/FilterDB.php",
        type: "POST",
        data: {			
            RequestType: "checkOverLoad",
            GradeID: GradeID,
            SchoolID: SchoolID,
            totalRows: totalRows,
            currentTT:formArry
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var dataSchool = data[0];
            $('#test').val(dataSchool);

        }
    });*/
    //alert(totalRows);
	checkTeacherDuplicate(totalRows)
    hidePleaseWait();
}

function checkTeacherDuplicate(totalRows){
    showPleaseWait();
    var subArr=$("#subArr").val();
    var TT1;
    
    var GradeID = $("#GradeID").val();
    var SchoolID = $("#SchoolID").val();
	var ClassID = $("#ClassID").val();
    var i;
    var subjSel;
    var formArry=[];
    //alert(formArry);
    for(i=1;i<totalRows+1;i++){
       var fieldVal="TT"+i;
       var valTT=$("#"+fieldVal).val();
	   //$("#TT"+i).attr('class', 'select2a_new');
        formArry.push(valTT);
       
    }
    //alert(formArry);
    var linksendValue="ajaxCall/FilterDB.php?RequestType=checkDuplicateTeacher&GradeID="+GradeID+"&SchoolID="+SchoolID+"&ClassID="+ClassID+"&totalRows="+totalRows+"&currentTT="+formArry+"&resultAsJson=1";   
   // alert(linksendValue);
    $.ajax({
            type: "GET",
            url: linksendValue,
            dataType: 'json',
            success: function(data, msg)
            {//alert(data.mrupfrontproceed);
                var totalRowsRes=data.totalRows;
                //var subArr=$("#TT1").val();
                //alert(totalRowsRes);
                var i;
                //$("#TT1").attr('class', 'select2a_red');
                var overLMT= data.failTT;
               // alert(overLMT);
                //var overLMT = ',1,2,3,4,5,6,';
                var temp = new Array();
                // this will return an array with strings "1", "2", etc.
                temp = overLMT.split(",");
//alert('tmp');
//alert(temp);
//alert('tmpend');
//for (a in temp ) {
    //temp[a];// = parseInt(temp[a], 10); // Explicitly include base as per Álvaro's comment
   // alert(temp[a]);
//}
				if(data.overLMT!=''){ 
					for (a in temp ) {
						var overld=temp[a];
						if(overld!=''){
							for(i=1;i<totalRows+1;i++){
									var fieldVal="TT"+i;
									//var valTT=$("#"+fieldVal).val();

									if(overld==fieldVal){
											//alert(data.overLMT);
											$("#TT"+i).attr('class', 'select2a_red_teacher');
									}

							}
						}
					}
				}
                   
            }
	});
    
    hidePleaseWait();
}

</script>
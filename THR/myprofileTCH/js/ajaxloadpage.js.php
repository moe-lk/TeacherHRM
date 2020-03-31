<script type="text/javascript">

function checkOverload(totalRows){
    showPleaseWait();
    var subArr=$("#subArr").val();
    var TT1;
    
    //TT1.style.backgroundColor = 'red';
   // print(subArr);
    //print_r(subArr);
   //alert(select);
    //alert('hi');
   // $("#TT2").attr('class', 'select2a_green');
  //  $("#TT3").attr('class', 'select2a_red');
    
    var GradeID = $("#GradeID").val();
    var SchoolID = $("#SchoolID").val();
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
    var linksendValue="ajaxCall/FilterDB.php?RequestType=checkOverLoad&GradeID="+GradeID+"&SchoolID="+SchoolID+"&totalRows="+totalRows+"&currentTT="+formArry+"&resultAsJson=1";   
    
    $.ajax({
            type: "GET",
            url: linksendValue,
            dataType: 'json',
            success: function(data, msg)
            {//alert(data.mrupfrontproceed);
                var totalRowsRes=data.totalRowsRes;
                //var subArr=$("#TT1").val();
               // alert(totalRows);
                var i;
                //$("#TT1").attr('class', 'select2a_red');
               // alert(data.overLMT);
				if(data.overLMT!=''){ 			
					for(i=1;i<totalRows+1;i++){
						var fieldVal="TT"+i;
						var valTT=$("#"+fieldVal).val();
						
						if(data.overLMT==valTT){
							//alert(data.overLMT);
							$("#TT"+i).attr('class', 'select2a_red');
						}
						
	
					}
				}
				
				 if(data.underLMT!=''){ 
				 	for(i=1;i<totalRows+1;i++){
						var fieldVal="TT"+i;
						var valTT=$("#"+fieldVal).val();
						
						if(data.underLMT==valTT){
							//alert(data.overLMT);
							$("#TT"+i).attr('class', 'select2a_green');
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
    
    hidePleaseWait();
}

</script>
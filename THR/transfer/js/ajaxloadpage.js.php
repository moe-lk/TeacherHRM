<script type="text/javascript">

function showSchoolTransferDet(){
	//alert("hi");
	//var cSLTBCoverChkBox=$("#cSLTBCoverChkBox").is(':checked');
	//var dBscTkflCntbton=Number(parseFloat($('#dBscTkflCntbton').val().replace(/[^0-9-.]/g, '')));
	var TransferRequestType=$('#TransferRequestType').val();
	var SchoolID=$('#SchoolID').val();
	//var iUsageID=Number($('#iUsageID').val());
//alert(cSLTBCoverChkBox);
	if(TransferRequestType){
		var linksend="ajaxCall/FilterDB.php?cat=showSchoolTransferDet&TransfrRequestType="+TransferRequestType+"&SchoolID="+SchoolID;
	 //alert(linksend);
		$('#zoneSchool').load(linksend, function() {
			//alert(linksend);
			changelable();
		});//
	}
		/*var linksend="ajaxCall/FilterDB.php?cat=showSchoolTransferDet&TransfrRequestType="+TransferRequestType+"&SchoolID="+SchoolID+"&resultAsJson=1";
		
		 $.ajax({
		type: "GET",
		url: linksend,
		dataType: 'json',
		success: function(data, msg)
		{
			//alert(data.SLTBCoverAmt);
		 $('#dSLTBCoverAmt').val(data.SLTBCoverAmt);
		  calculateSubTotal();
		  $('#cSLTBCoverChkBox').each(function(){ this.checked = true; });
		}
		});		*/
	/*}else{
			//$('#sltb_cover').hide();
			$('#cSLTBCoverChkBox').each(function(){ this.checked = false; });
			$('#dSLTBCoverAmt').val('0.00');
			calculateSubTotal();
	}*/

}

function changelable(){
	//alert("hi");
	//var cSLTBCoverChkBox=$("#cSLTBCoverChkBox").is(':checked');
	//var dBscTkflCntbton=Number(parseFloat($('#dBscTkflCntbton').val().replace(/[^0-9-.]/g, '')));
	var TransferRequestType=$('#TransferRequestType').val();
	var SchoolID=$('#SchoolID').val();
	//var iUsageID=Number($('#iUsageID').val());
//alert(cSLTBCoverChkBox);
	
	var linksend="ajaxCall/FilterDB.php?cat=showLable&TransfrRequestType="+TransferRequestType+"&SchoolID="+SchoolID+"&resultAsJson=1";
	$('#zoneSchoolLable').load(linksend, function() {
			//alert(linksend);
		changeSchool();
	});	
}

function changeSchool(){
	//alert("hi");
	//var cSLTBCoverChkBox=$("#cSLTBCoverChkBox").is(':checked');
	//var dBscTkflCntbton=Number(parseFloat($('#dBscTkflCntbton').val().replace(/[^0-9-.]/g, '')));
	var TransferRequestType=$('#TransferRequestType').val();
	var SchoolID=$('#SchoolID').val();
	//var iUsageID=Number($('#iUsageID').val());
//alert(cSLTBCoverChkBox);
	
	var linksend="ajaxCall/FilterDB.php?cat=changeSchool&TransfrRequestType="+TransferRequestType+"&SchoolID="+SchoolID+"&resultAsJson=1";
	$('#changeSchool').load(linksend, function() {
			//alert(linksend);
		//changeSchool();
	});	
}
</script>
var xmlHttp


function show_district(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_districtTxt)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_districtTxt()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_district").innerHTML=xmlHttp.responseText 
	} 
}
// ADDED TO GET SUBJECTS
function show_Appsubj(str,icid,srch){
	// alert('this');
    var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_AppSubject)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
}
window.alert(xmlHttp.readyState);
function show_AppsubjTxt(){
	// window.alert('This');
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		
	document.getElementById("SubAppDiv").innerHTML=xmlHttp.responseText 
	}
}

//END GETTING SUBJECTS
function show_zone(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_zoneTxt)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_zoneTxt()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_zone").innerHTML=xmlHttp.responseText 
	} 
}



function show_cences(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_censesTxt)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_censesTxt()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_showInstitute").innerHTML=xmlHttp.responseText 
	} 
}

//getSubCatID
function show_division(str,icid,srch)
{ //alert(icid);
var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
xmlHttp=GetXmlHttpObject(show_divisionTxt)
xmlHttp.open("GET", url , true)
xmlHttp.send(null)
} 

function show_divisionTxt()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
{ 
document.getElementById("txt_division").innerHTML=xmlHttp.responseText 
} 
}

function show_divisionC(str,icid,srch)
{ //alert(icid);
var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
xmlHttp=GetXmlHttpObject(show_divisionCTxt)
xmlHttp.open("GET", url , true)
xmlHttp.send(null)
} 

function show_divisionCTxt()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
{ 
document.getElementById("txt_divisionC").innerHTML=xmlHttp.responseText 
} 
}

function show_changepw(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateChangedshow_changepw)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
	
} 

function stateChangedshow_changepw() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("txt_changepw").innerHTML=xmlHttp.responseText 
	} 
}

function show_dealerDischangepw(str,icid,srch)
{ //alert(str);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateshow_dealerDischangepw)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function stateshow_dealerDischangepw()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
		document.getElementById("div_passwd").innerHTML=xmlHttp.responseText 
	} 
}

//chathura
//changeItemIdBySubCatIDAndCatID


function stateChangedshow_file()
{
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
    {
        document.getElementById("showfile").innerHTML=xmlHttp.responseText
    }
}
//end files


//end og change room type

function GetXmlHttpObject(handler)
{ 
var objXmlHttp=null

if (navigator.userAgent.indexOf("Opera")>=0)
{
alert("This example doesn't work in Opera") 
return 
}
if (navigator.userAgent.indexOf("MSIE")>=0)
{ 
var strName="Msxml2.XMLHTTP"
if (navigator.appVersion.indexOf("MSIE 5.5")>=0)
{
strName="Microsoft.XMLHTTP"
} 
try
{ 
objXmlHttp=new ActiveXObject(strName)
objXmlHttp.onreadystatechange=handler 
return objXmlHttp
} 
catch(e)
{ 
alert("Error. Scripting for ActiveX might be disabled") 
return 
} 
} 
if (navigator.userAgent.indexOf("Mozilla")>=0)
{
objXmlHttp=new XMLHttpRequest()
objXmlHttp.onload=handler
objXmlHttp.onerror=handler 
return objXmlHttp
}
} 

function showContactTimer() {
	//alert('bar');
	var loader = document.getElementById('loadBar');
	loader.style.display = 'block';
	sentTimer = setTimeout("hideContactTimer()",2000);
}
function hideContactTimer () {
	var loader = document.getElementById('loadBar');
	loader.style.display = "none";
}
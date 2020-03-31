var xmlHttp


function show_available(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_availableTxt)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_availableTxt()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_available").innerHTML=xmlHttp.responseText 
	} 
}

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

/*--------First--------*/

function show_zoneF(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_zoneTxtF)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_zoneTxtF()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_zoneF").innerHTML=xmlHttp.responseText 
	} 
}

function show_divisionF(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_divisionTxtF)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_divisionTxtF()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_divisionF").innerHTML=xmlHttp.responseText 
	} 
}

function show_cencesF(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(show_censesTxtF)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function show_censesTxtF()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
	document.getElementById("txt_showInstituteF").innerHTML=xmlHttp.responseText 
	} 
}
/*--------End First-----------*/


/*----------------------*/
function show_divisionAdd(str,icid,srch)
{ //alert(icid);
var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
xmlHttp=GetXmlHttpObject(show_divisionAddTxt)
xmlHttp.open("GET", url , true)
xmlHttp.send(null)
} 

function show_divisionAddTxt()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
{ 
document.getElementById("txt_divisionAdd").innerHTML=xmlHttp.responseText 
} 
}

function show_divisionAddTmp(str,icid,srch)
{ //alert(icid);
var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
xmlHttp=GetXmlHttpObject(show_divisionAddTmpTxt)
xmlHttp.open("GET", url , true)
xmlHttp.send(null)
} 

function show_divisionAddTmpTxt()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
{ 
document.getElementById("txt_divisionAddTmp").innerHTML=xmlHttp.responseText 
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
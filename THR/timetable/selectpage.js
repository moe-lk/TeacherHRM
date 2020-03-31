var xmlHttp

function show_changepw(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateChangedshow_changepw)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
	
} 
//chathura other invoice

//getSubCatID
function showLink(str,icid,srch)
{ //alert(icid);
var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
xmlHttp=GetXmlHttpObject(stateshowLink)
xmlHttp.open("GET", url , true)
xmlHttp.send(null)
} 

function stateshowLink()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
{ 
document.getElementById("txt_link").innerHTML=xmlHttp.responseText 
} 
}

function show_classes(str,icid,srch)
{ //alert(icid);
var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
xmlHttp=GetXmlHttpObject(stateshowclasses)
xmlHttp.open("GET", url , true)
xmlHttp.send(null)
} 

function stateshowclasses()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
{ 
document.getElementById("txt_classes").innerHTML=xmlHttp.responseText 
} 
}


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

function show_periodCount(str,icid,srch)
{ //alert(srch);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateshow_periodCount)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function stateshow_periodCount()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
		document.getElementById("txt_periodCount").innerHTML=xmlHttp.responseText 
	} 
}

function show_incharge(str,icid,srch)
{ //alert(srch);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateshow_incharge)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function stateshow_incharge()
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
		document.getElementById("txt_incharge").innerHTML=xmlHttp.responseText 
	} 
}

//chathura
//changeItemIdBySubCatIDAndCatID
function productcatview(str,icid,srch)
{ //alert(icid);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateproductcatview)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function stateproductcatview() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("txt_productCate").innerHTML=xmlHttp.responseText 
	} 
}

function showlableValue1(str,icid,srch)
{ alert("Lable 1 will display now");
//setTimeout(showlableValue1,20);
showContactTimer();
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	
	xmlHttp=GetXmlHttpObject(stateshowlableValue1)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function stateshowlableValue1() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("lable1Txt").innerHTML=xmlHttp.responseText 
	} 
}
function showlableValue2(str,icid,srch)
{ //alert(icid);
alert("Lable 2 will display now");
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(stateshowlableValue2)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function stateshowlableValue2() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("lable2Txt").innerHTML=xmlHttp.responseText 
	} 
}
//changeSalesPkgItemID
function webcatview(str,icid,srch)
{ //alert(srch);
	var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch	
	xmlHttp=GetXmlHttpObject(statewebcatview)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
} 

function statewebcatview() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("txt_webcat").innerHTML=xmlHttp.responseText 
	} 
}

//end of chathura

function stateChangedshow_changepw() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("txt_changepw").innerHTML=xmlHttp.responseText 
	} 
}

//show_file
function show_file(str,icid,srch,tolpe)
{ //alert(icid);
    var url="getpage.php?sid=" + Math.random() + "&q=" + str + "&iCID=" + icid + "&srch=" + srch    + "&tolpe=" + tolpe
    xmlHttp=GetXmlHttpObject(stateChangedshow_file)
    xmlHttp.open("GET", url , true)
    xmlHttp.send(null)
  
}

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
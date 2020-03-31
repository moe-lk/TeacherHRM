<?php 
include ('../db_config/DBManager.php');
include("../db_config/my_functions.php");
date_default_timezone_set("Asia/Colombo");
$db = new DBManager();
$currDate = date('Y-m-d');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transfer Letter</title>
<style type="text/css">
.first{ 
	/*color: blue;
	font-size:12px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	*/
    font-size: 12px;
	color: #626379;
	font-family: Arial, Helvetica, sans-serif;
 }
 
 .firstHead{ 
	/*color: blue;
	font-size:12px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	*/
    font-size: 16px;
	color: #626379;
	font-weight:bold;
	font-family: Arial, Helvetica, sans-serif;
 }


</style>
</head>

<body onLoad="javascript:print();">
<table width="800" align="center" cellpadding="1" cellspacing="1" class="first">
  <tr>
    <td width="59%">&nbsp;</td>
    <td width="41%">මගේ අංකය :-.............................................</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>කලාප අධ්‍යාපන කාර්යාලය</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>..........................................................</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>20..................................</td>
  </tr>
  <tr>
    <td>....................................................................... විදුහලේ</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>විදුහල්පති මගින්</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>.....................................................................................</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>යොමුව :- .....................................................................</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><u class="firstHead">ගුරු සේවයේ ස්ථාන මාරුවීම් - අන්තර් කලාප</u></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">............................ පළාත් අධ්‍යාපන ලේකම්ගේ අංක ............................................................ හා ................. දින දරන ලිපිය අනුව වහාම ක්‍රියාත්මක වන පරිදි ගුරුසේවයේ ඔබ පහත සඳහන් විදුහලට මාරුකරන ලද බව මෙයින් දන්වමි.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">පත්වීමේ වර්ගය		-	.........................................................................................</td>
  </tr>
  <tr>
    <td colspan="2">මාරු කරන ලද විදුහල	-	.........................................................................................</td>
  </tr>
  <tr>
    <td colspan="2">විදුහලේ ලිපිනය		-	.........................................................................................</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">02.	නියමිත දින එහි ගොස් රාජකාරි භාරගෙන ඒ බව දින 03 ක් ඇතුළත විදුහල්පති මගින් මා වෙත පිටපත් 02 කින් යුතුව දන්වා එවිය යුතුය. වහාම ක්‍රියාත්මක වන පරිදි දී ඇති ස්ථාන මාරුවීම සම්බන්ධයෙන් මෙම ලිපිය ලැබී දින 03 ක් තුළ වැඩ භාරගෙන ඒ බව දන්වා එවිය යුතුයි. මෙම ලිපියේ සඳහන් මගේ අංකය ඔබගේ වැඩ භාර ගැනීමේ ලිපියේ සඳහන් කළ යුතුයි.</td>
  </tr>
  <tr>
    <td colspan="2">03.	මාරුවීම් දිනට ඔබ භාරයේ ඇති විදුහලට අයත් පොත් පත්, මුදල්, රිසිට් පොත්, ලිපිද්‍රව්‍ය යනාදිය නිසියාකාරව විදුහල්පති ට හෝ ඊළඟ ප්‍රධානියාට භාර දී එය භාරගත් බවට ලියකියවිලි ලබා ගත යුතුයි.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>කලාප අධ්‍යාපන අධ්‍යක්ෂ</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>කලාප අධ්‍යාපන කාර්යාලය</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>..........................................</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>පිටපත් :-</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">1.	දැනට සේවය කරන විදුහලේ විදුහල්පති	-	අදාළ ගුරු භවතා ඔබ පාසලින් නිදහස් කොට නව සේවා 
      ස්ථානයේ රාජකාරි භාර ගැනීමට හැකිවන පරිදි නි.කි.ස.
</td>
  </tr>
  <tr>
    <td colspan="2">2.	ස්ථාන මාරුකරන ලද විදුහලේ විදුහල්පති	-	දින 03 ක් ඇතුළත වැඩ භාර නොගතහොත් වහාම මා 
							වෙත දැනුම් දීම සඳහා.
</td>
  </tr>
  <tr>
    <td colspan="2">3.	පළාත් අධ්‍යාපන අධ්‍යක්ෂ			-	දැ.ගැ.ස. සහ අ.ක.ස.</td>
  </tr>
  <tr>
    <td colspan="2">4.	කොට්ඨාස අධ්‍යාපන අධ්‍යක්ෂ		-	දැ.ගැ.ස. සහ අ.ක.ස.</td>
  </tr>
  <tr>
    <td colspan="2">5.	ගණකාධිකාරී				-	දැ.ගැ.ස. සහ අ.ක.ස.</td>
  </tr>
  <tr>
    <td colspan="2">6.	අධ්‍යක්ෂ/සැලසුම් අංශය			-	දැ.ගැ.ස. සහ අ.ක.ස.</td>
  </tr>
  <tr>
    <td colspan="2">7.	අධ්‍යක්ෂ/සංවර්ධන</td>
  </tr>
  <tr>
    <td colspan="2">8.	පෞද්ගලික ලිපිගොනුවට</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
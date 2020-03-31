<?php
session_start();
//echo md5('test');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>National Education Management Information System | Ministry of Education Sri Lanka</title>
        <link href="css/ems.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="wrapper"><!-- wrapper -->
            <div class="headerOutBox" style="background-color:#92495C;"><!-- #0191b5 headerOutBox -->
                <div class="header"><img src="images/top-img.png" width="980" height="150" alt="header"></div>
            </div><!-- headerOutBox End -->
            <div class="midOutBox"><!-- midOutBox -->
                <div class="midBox"><!-- midBox -->
                <div class="midLeftBox"><!-- midLeftBox -->
                        <div class="proBox">
                            <div class="proBoxTitel"></div>
                            <div class="proBoxImg"><width="210" height="110"></div>
                            <div class="proBoxText"></div>
                            <div class="proBoxText2"></div>
                        </div>

                        <div class="proBox">
                            <div class="proBoxTitel"></div>
                            <div class="proBoxImg"><width="210" height="110"></div>
                            <div class="proBoxText"></div>
                            <div class="proBoxText2"></div>
                        </div>
                    </div><!-- midLeftBox End -->
                    <div class="midCenterBox"><!-- midCenterBox -->

<!--<div class="midImg"><img src="images/pro-2.jpg" width="445" height="280"></div>-->

                        <div class="midBoxText">
                            <div class="midTextTitel">Welcome to NEMIS</div>
                            <div class="midTextBox">National Education Management Information System, abbreviated, as NEMIS is an online web portal that automates the entire end-to-end management of education data and related administration functions. <br><br>
                                The primary aim of the NEMIS is to collect, provide and analyze real-time data for better decision-making. Furthermore NEMIS automates the manual processes of administration functions which decrease the wastage of time and resources. <br><br>
                                NEMIS consists of modules which handle basically all the entities related to education including students, teachers, principals, schools, educational offices at zonal and provincial levels and the Ministry of Education. <br><br>
                                NEMIS is the first   <strong>Nation-wide</strong> initiative towards digitized education.</div>
                        </div>
                    </div><!-- midCenterBox End -->
                    <div class="midRightBox"><!-- midRightBox -->
                        <form id="login" name="login" action="login.php?request=signIn" method="POST">
                            <div class="midRightInnerBox">
                                <div class="midRightInnerBoxTitel" style="margin-top:10px;">Login To NEMIS Management Portal</div>
                                <?php if ($_SESSION['error_msg']) { ?><div class="midRightInnerBoxTitel" style="margin-top:5px; color:#C03; font-size:12px;"><?php echo $_SESSION['error_msg'];
                                $_SESSION['error_msg'] = ""; ?></div><?php } ?>
                                <div class="foumBox">
                                    <div class="foumBoxTitel">Username</div>
                                    <div class="foumField"><input name="userName" id="userName" type="text"></div>
                                </div>
                                <div class="foumBox">
                                    <div class="foumBoxTitel">Password</div>
                                    <div class="foumField"><input name="txtpassword" id="txtpassword" type="password"></div>
                                </div>
                                <input type="submit" value="LOGIN" class="sumit-button">
                                <div class="midLinkText" style="margin-bottom:5px;"><strong>Forgot your password?</strong><br>
                                    <a href="#">Contact your provincial NEMIS coordinator to reset your password.</a></div>
                            </div>
                        </form>
                        <div class="midRightInnerBox"><!--
                        <div class="midRightInnerBoxTitel" style="margin-top:10px;">Request For Registration</div>-->
                            <div class="rightBoxButton"><a href="webuser/">
                                    <div class="rightBoxButtonIcon"><img src="images/icon-1.png" width="50" height="40"></div>
                                    <div class="rightBoxButtonText">Coordinator List</div>
                                </a>
                            </div>
                            <div class="rightBoxButton"><a href="register/">
                                    <div class="rightBoxButtonIcon"><img src="images/icon-2.png" width="50" height="40"></div>
                                    <div class="rightBoxButtonText">User Registration</div>
                                </a>
                            </div>
                            <div class="rightBoxButton"><a href="webuser/contactUs-1.html">
                                    <div class="rightBoxButtonIcon"><img src="images/icon-2.png" width="50" height="40"></div>
                                    <div class="rightBoxButtonText">Contact Us</div>
                                </a>
                            </div>
                        </div>
                    </div><!-- midRightBox End -->
                </div><!-- midBox End-->
            </div><!-- midOutBox End-->
            <div class="footerOutBox" style="background-color:#92495C;">
                <div class="footerText">Developed by Department of Computer Science and Engineering, University of Moratuwa</div>

            </div>
        </div> <!-- wrapper End -->
    </body>
</html>

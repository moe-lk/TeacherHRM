<div class="mcib_top">
    <div id="navcontainer">
        <ul id="navlist">
        <li id="active"><a href="../module_main.php" id="current" style="border-left:none; border-top-left-radius:6px; margin-left:-10px;"><img  src="../images/home.png" width="26" height="20" alt="dashboard" /></a></li>
        <li><a href="../schoolnet/"><img style="" src="../cms/images/fixed_images/icon/dashboard.png" width="26" height="20" alt="dashboard" />&nbsp;Reports</a>
            <!--<ul id="subnavlist">
                <li id="subactive"><a href="#" id="subcurrent">Subitem one</a></li>
                <li><a href="#">Subitem two</a></li>
                <li><a href="#">Subitem three</a></li>
                <li><a href="#">Subitem four</a></li>
            </ul>-->
        </li>
        <?php if($ModuleFolder=='timetable'){?>
        <li><a href="#" style="background-color:#003366; color:#fff;"><img style="margin-left: -5px;" src="../cms/images/fixed_images/icon/element.png" width="26" height="15" alt="timetable" />&nbsp; Timetable</a></li>
        <?php }else{?>
        <li><a href="../timetable/"><img style="margin-left: -5px;" src="../cms/images/fixed_images/icon/element.png" width="26" height="15" alt="timetable" />&nbsp; Timetable</a></li>
        <?php }?>
        <li><a href="../retirement/"><img style="margin-left: -5px;" src="../cms/images/fixed_images/icon/element.png" width="26" height="15" alt="timetable" />&nbsp; Retirement</a></li>
        <li><a href="../leave/"><img style="margin-left: -5px;" src="../cms/images/fixed_images/icon/element.png" width="26" height="15" alt="timetable" />&nbsp; Leave</a></li>
        <li><a href="../administrator/"><img style="margin-left: -5px;" src="../cms/images/fixed_images/icon/element.png" width="26" height="15" alt="timetable" />&nbsp; Administrator</a></li>
        </ul>
    </div>
</div>
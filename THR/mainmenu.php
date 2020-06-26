<?php ?>
<div class="mcib_top" <?php if ($ModuleFolder == 'timetable' and $pageid == '2') { ?>style="width:980px; height:40px;"<?php } ?>>
    <?php if($pageid!='9C'){        
        ?>
    
    <div id="navcontainer">
        <ul id="navlist">



            <?php
            $AccessRoleID = $_SESSION['AccessRoleID'];

// get all parent menu records for loged user
            $sqlDyn = "SELECT
                        TG_DynMenu.ID,
                        TG_DynMenu.Icon,
                        TG_DynMenu.Title,
                        TG_DynMenu.PageID,
                        TG_DynMenu.Url,
                        TG_DynMenu.ParentID,
                        TG_DynMenu.IsParent,
                        TG_DynMenu.ShowMenu,
                        TG_DynMenu.ParentOrder,
                        TG_DynMenu.ChildOrder,
                        TG_DynMenu.FOrder
                        FROM
                        TG_DynMenu
                        INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
                        WHERE
                        TG_Privilage.AccessRoleID = $AccessRoleID AND TG_DynMenu.IsParent = 1 AND TG_DynMenu.ParentID = 0 AND TG_DynMenu.ShowMenu = 1";
            $stmt = $db->runMsSqlQuery($sqlDyn);
            $count = 0;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $count++;
                $rowid = $row['ID'];
                $icon = $row['Icon'];
                $title = $row['Title'];
                $page_id = $row['PageID'];
                $url = $row['Url'];
                $parent_id = $row['ParentID'];
                $is_parent = $row['IsParent'];
                $show_menu = $row['ShowMenu'];


                // var_dump($title);
                $active_class = "";
                //echo basename($_SERVER['REQUEST_URI']);
                //echo $_SERVER['REQUEST_URI'];
                // echo $url;
                // "<br>";
                $rq_url = $_SERVER['REQUEST_URI'];
                $rq_url_arr = explode('/', $rq_url);
                if (basename($_SERVER['REQUEST_URI']) == $url) {

                    $active_class = "active";
                }
                // var_dump($rq_url_arr);
                if (in_array($url, $rq_url_arr)) {
                    $active_class = "active";
                }

                if ($rowid == 4) {
                    $url = "myprofile/personalInfo-1--" . $NICUser . ".html";
                }

                if ($rowid == 15) {
                    $url = "myprofileTCH/personalInfo-23--" . $NICUser . ".html";
                }

                $url = '../' . $url;


                if ($count == 1) {
                    ?>

                    <li id="active">
                        <a href="../module_main.php" id="current" style="border-left:none; border-top-left-radius:6px; margin-left:-10px; ">
                            <img  src="../images/home.png" width="26" height="26" alt="dashboard" style="margin-top:2px;"/>
                        </a>
                    </li>
                <?php } ?>
                <li><a href="<?php echo ($url); ?>" class="<?php echo $active_class; ?>"><?php echo $title; ?></a></li>
                <!-- <li><a href="teacherService">Service Records</a></li> -->
                <?php
            }
            ?> 
            <!-- <li><a href="teacherServive/index.php">Service Records</a></li> -->
            <?php
            if ($count == 0) {
                ?>
                <li id="active">
                    <a href="../module_main.php" id="current" style="border-left:none; border-top-left-radius:6px; margin-left:-10px; ">
                        <img  src="../images/home.png" width="26" height="26" alt="dashboard" style="margin-top:2px;"/>
                    </a>
                </li>
                <?php
            }
            ?>



        </ul>
    </div>
<?php }?>
</div>
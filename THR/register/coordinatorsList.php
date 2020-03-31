<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$sqlList="SELECT * From TG_CoordinatorsList where Location!='' order by OrderNumber asc";
$TotaRows=$db->rowCount($sqlList);
?>


<div class="main_content_inner_block">
        <table width="945" cellpadding="0" cellspacing="0">
			<tr>
               <td colspan="2" align="center" style="border-bottom: 1px; border-bottom-style: solid; color:#000; font-size:16px; font-weight:bold;">Coordinator List</td>
            </tr>
            <tr>
              <td colspan="2" align="center" height="15"></td>
            </tr>
                <?php 
					
					$i=1;
					$stmt = $db->runMsSqlQuery($sqlList);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {a	 
				?>
                
                <tr>
                    <td colspan="2" bgcolor="#FFFFFF" style="border-bottom:1px; border-bottom-style:solid;"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="23%"><strong><?php echo trim($row['Location']); ?></strong></td>
                        <td width="77%"><strong><?php echo trim($row['Title']); ?>&nbsp;<?php echo trim($row['NameWithInitials']); ?></strong></td>
                      </tr>
                      <tr>
                        <td rowspan="6">&nbsp;</td>
                        <td><?php echo trim($row['Designation']); ?></td>
                      </tr>
                      <tr>
                        <td><?php echo trim($row['Address1']); ?></td>
                      </tr>
                      <tr>
                        <td><?php echo trim($row['Address2']); ?></td>
                      </tr>
                      <tr>
                        <td><?php echo trim($row['TpNumber']); ?></td>
                      </tr>
                      <tr>
                        <td><?php echo trim($row['EmailAdd']); ?></td>
                      </tr>
                      <tr>
                        <td height="10"></td>
                      </tr>
                    </table></td>
          </tr>
          <tr>
            <td colspan="2" bgcolor="#FFFFFF" height="10"></td>
          </tr>
          <?php }?>
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
              </table>
    </div>
    
</div>
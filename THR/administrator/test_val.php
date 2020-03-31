

        <link type='text/css' href='../assets/css/dashboard.css' rel='stylesheet' media='screen'/>
        <link rel="stylesheet" href="../assets/css/jquery-ui.css">

        <script src="../assets/js/jquery-latest.min.js" type="text/javascript"></script>
        <script src="../assets/js/jquery-ui.js"></script>
        <script src="../assets/js/back/script.js"></script>

        <style>
			.fields_errors{
				border-color: rgba(229, 103, 23, 0.8);
				box-shadow: 0 1px 1px rgba(229, 103, 23, 0.075) inset, 0 0 8px rgba(229, 103, 23, 0.6);
				outline: 0 none;
			}
		
		</style>

<form action="" method="post" name="formBanner" id="formBanner" enctype="multipart/form-data" autocomplete="off">
                <!-- validation error both server side & client side -->

                <div class="error">
                    <div id="dialog" title="Error" style="display: none;">
                        <p>Please fill required information.</p>
                    </div>
                </div><!--enderror-->

                <!-- end validation error -->


                <table border="0" cellspacing="0" cellpadding="0">

                        <tr valign="top">
                            <td><h2>Title</h2><input type="submit" class="saveButton" value="Save"/></td>
                        </tr>

                        <tr valign="top">
                            <td><input class="input2" name="vTitle" id="vTitle" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td><h2>Order</h2></td>
                        </tr>
                        <tr>
                            <td>
                               <input class="input2" name="iOrder" id="iOrder" type="text" value="" onkeypress="return numbersonly(event)" />
                            </td>
                        </tr>
                        <tr>
                            <td><h2>Status</h2></td>
                        </tr>
                        <tr>
                            <td>
                                <select class="select1" name="cEnable" id="cEnable">
									<option value="">0</option>
                                    <option value="1">1</option>
                                </select>
                            </td>
                        </tr>
                    </table>
        </form>

<script>

    $("#formBanner").submit(function(event) {
        var dialogStatus = false;
        var vTitle = trim($("#vTitle").val());
        var iOrder = trim($("#iOrder").val());
		var cEnable = trim($("#cEnable").val());

        //$("#vUserName").attr('class', 'fields_errors');
        if (vTitle == "") {
            $("#vTitle").attr('class', 'input2_error');
            dialogStatus = true;
        }

        if (iOrder == "") {
            $("#iOrder").attr('class', 'input2_error');
            dialogStatus = true;
        }
		
		if (cEnable == "") {
            $("#cEnable").attr('class', 'input2_error');
            dialogStatus = true;
        }

        if (dialogStatus) {
            $("#dialog").dialog({
                modal: true
            });
            event.preventDefault();
        }

    });

    function numbersonly(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) { //if the key isn't the backspace key (which we should allow)
            if (unicode < 48 || unicode > 57) //if not a number
                return false //disable key press
        }
    }

</script>
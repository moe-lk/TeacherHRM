var form = "";

var submitted = false;

var error = false;

var error_message = "";



function trim(str)

{

    return str.replace(/^\s+|\s+$/g,'');

}



function check_input(field_name, field_size, message) {

  if (form.elements[field_name]) {

    var field_value = form.elements[field_name].value;



    if (field_value == '' || field_value.length < field_size || trim(field_value) == '') {

      error_message = error_message + "* " + message + "\n";

      error = true;

    }

  }

}

function check_input_captch(field_name1, field_name2, message) {

    var field_value1 = form.elements[field_name1].value;

	var field_value2 = form.elements[field_name2].value;



    if (field_value1 != field_value2) {

      error_message = error_message + "* " + message + "\n";

      error = true;

    }

}


function check_radio(field_name, message) {

  var isChecked = false;



  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {

    var radio = form.elements[field_name];



    for (var i=0; i<radio.length; i++) {

      if (radio[i].checked == true) {

        isChecked = true;

        break;

      }

    }



    if (isChecked == false) {

      error_message = error_message + "* " + message + "\n";

      error = true;

    }

  }

}



function check_select(field_name, field_default, message) {

	//alert('test');

  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {

    var field_value = form.elements[field_name].value;



    if (field_value == field_default) {

      error_message = error_message + "* " + message + "\n";

      error = true;

    }

  }

}



function check_password(field_name_1, field_name_2, field_size, message_1, message_2) {

  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {

    var password = form.elements[field_name_1].value;

    var confirmation = form.elements[field_name_2].value;



    if (password == '' || password.length < field_size) {

      error_message = error_message + "* " + message_1 + "\n";

      error = true;

    } else if (password != confirmation) {

      error_message = error_message + "* " + message_2 + "\n";

      error = true;

    }
	/*else if (password == confirmation){
     
      var url="save2.php?password=" +password;
		var html = $.ajax({url:url,async: false});		
				if(html.responseText=="0"){
					error2 = true;

					error_message2 = error_message2; 
				}else{
					error2 = false;
				} 

    }*/

  }

}



function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_1, message_2, message_3) {

  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {

    var password_current = form.elements[field_name_1].value;

    var password_new = form.elements[field_name_2].value;

    var password_confirmation = form.elements[field_name_3].value;



    if (password_current == '' || password_current.length < field_size) {

      error_message = error_message + "* " + message_1 + "\n";

      error = true;

    } else if (password_new == '' || password_new.length < field_size) {

      error_message = error_message + "* " + message_2 + "\n";

      error = true;

    } else if (password_new != password_confirmation) {

      error_message = error_message + "* " + message_3 + "\n";

      error = true;

    }

  }

}

//for amount fields

function check_amount(field_name_1, message_1){

	var uInput = form.elements[field_name_1].value;

	//var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;

	var alphaExp = /^[0-9\.]+$/;

	if(uInput.match(alphaExp)){

		error = false;

	}else{

		error = true;

		error_message = error_message + "* " + message_1 + "\n";

	}

}

//-----------------

//for check_special_char fields

function check_special_char(field_name_1, message_1){

	var uInput = form.elements[field_name_1].value;	

	var iChars = "!@#$%^&*()+=[]\\\';/{}|\":<>?";

	for (var i = 0; i < uInput.length; i++) {

		if (iChars.indexOf(uInput.charAt(i)) != -1) {

			error = true;

			error_message = error_message + "* " + message_1 + "\n";

		}

   }	

}

//-----------------

//for email validates

function check_email1(field_name_1, message_1){

	var uInput = form.elements[field_name_1].value;

	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
 
	if(uInput.match(emailExp)){
		var id = document.getElementById("id").value;
      if(id==""){
		var url="save1.php?uInput=" + uInput;
		var html = $.ajax({url:url,async: false});		

				if(html.responseText=="0"){
					error1 = true;

					error_message1 = error_message1; 
				}else{
					error1 = false;
				} 
	  }else{
		  var url="save1.php?uInput=" + uInput+ "&id=" + id;
		 var html = $.ajax({url:url,async: false});		
		  }

	}else{

		error = true;

		error_message = error_message + "* " + message_1 + "\n";

	}
 

}
function check_email(field_name_1, message_1){

	var uInput = form.elements[field_name_1].value;

	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;

	if(uInput.match(emailExp)){

		error = false;

	}else{

		error = true;

		error_message = error_message + "* " + message_1 + "\n";

	}

}


function check_input_num_validate(field_name, field_size, message) {
  //if (form.elements[field_name]) {
    var field_value = form.elements[field_name].value;
    if (isNaN(field_value) || field_value == '' || field_value.length < field_size || trim(field_value) == '' ) {
// if (field_value == '') {
// alert("ok");
      error_message = error_message + "* " + message + "\n";
      error = true;
// alert(error_message);
    }
  //}
}

function check_agree(field_name, message){
	var isChecked = false;
	var radio = form.elements[field_name];
	 if (radio.checked == false) {

		 error_message = error_message + "* " + message + "\n";

      error = true;
	 }
}



 




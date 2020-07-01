function addtext(){
					var select = document.getElementById("t1");

					var options = [];
					var options1 = [];

					if(sessionStorage.addedList!=null){

						options1.push(sessionStorage.addedList);
					}
					var option = document.createElement('option');


						//var data = '<option value="' + escapeHTML(i) +'">" + escapeHTML(i) + "</option>';
					option.text = option.value = document.getElementById("newSchool").value;

					options.push(option.outerHTML);

					options1.push(option.outerHTML);
					//alert(options1);


					select.insertAdjacentHTML('beforeEnd', options.join('\n'));
					sessionStorage.addedList =options1;

				}

				function loadselect(){
					var select = document.getElementById("t1");
					var options=sessionStorage.addedList;
					var temp = new Array();
					temp=options.split(',');
					select.insertAdjacentHTML('beforeEnd', temp.join('\n'));
					select.selectedIndex =sessionStorage.selindex;

				}

				function setlastitem(){
					var index=document.getElementById("t1").selectedIndex;
					sessionStorage.selindex =index;

				}
				window.alert("This");
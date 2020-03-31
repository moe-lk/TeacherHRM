
function mainmenu(){
$(" #nav ul ").css({display: "none"}); // Opera Fix
$(" #nav li ").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(200);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
}

//function - change icon of menu element
function changeIcon(sElement, imgParth){
		var fullPath = $('#'+sElement).children("img:first").attr('src');
		var pathSplit = fullPath.split("/");
		var imgFullName = pathSplit[pathSplit.length-1];
		var imgFullNameSplit = imgFullName.split(".");
		var imgName = imgFullNameSplit[0];
				
		var str = imgName;
		var newStr = imgName +"1";
				
		$('#'+ sElement +'_img').attr("src",imgParth + newStr +".png");
}

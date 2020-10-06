$(document).ready(function(){
    $(".up,.down").click(function(){
        var row = $(this).parents("tr:first");
        if ($(this).is(".up") ) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }
    });
	$(".delrow ").click(function() {
                   $(this).parents("tr").remove();				 
        });
});
function ventanaEmergente(URL, ancho, alto, left, top) {
window.open(URL, "_blank", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width='+ancho+',height='+alto+',left ='+left+',top = '+top);
}

$("a.print-top").click(function(){ window.print(); });


function busqueda(){

	var tit = $("#titulo").html();

	$("input[type='search']#busqueda").keyup(function(){

		var val = $(this).val();

		if( val.length > 0 ){
			$("#titulo").html(tit+"<span style='text-transform: none;'><br><br><strong>B&uacute;squeda: </strong>" + val + "</span>");
		}else{
			$("#titulo").html(tit);
		}
	});

}
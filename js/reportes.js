		$(document).ready(function(){
			load(1);
		});

		function load(page){
			var q= $("#id_categoria").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_reportes.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}

		function exportar_excel(){
			var id_categoria= $("#id_categoria").val();
			url="reporte_stock.php?id_categoria="+id_categoria;
			//window.open(url, "Reporte excel", "width=300, height=200")
			
			window.location=url; 
		}

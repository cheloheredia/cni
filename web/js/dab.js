/*
 * @author Marcelo Heredia
 * Jan, 2014
*/
$(function () {
    $("#recinto").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: buscarautocompletes,
                dataType: "json",
                data: {
                    term : request.term,
                    opt : 'recinto'
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: itemSeleccionado,
        focus: itemMarcado
    });
    $('#enviardab').click(function(){
        var error = '';
        if ($('#recinto').val().length < 1) {
            error += 'El campo recinto es obligatorio. ';
        }
        if ($('#archivodab').val().length < 1) {
            error += 'No selecciono ningun archivo. ';
        } else {
            if (verExtencion($('#archivodab')[0].files[0].name) == false) {
                error += 'No es un archivo excel. ';
            }
        }
        if (error != '') {
            $("#respuestadab").html($('<br><span class="mensaje">'+error+'</span><br>'));
        } else {
            var archivos = $('#archivodab');
            var archivo = archivos[0].files[0];
            var datos = new FormData();
            datos.append('archivo',archivo);
            datos.append('recinto',$('#recinto').val());
            datos.append('opt',$('#optdab').val());
            var url = 'client/subir.php';
            $.ajax({
                url:url,
                type:'POST',
                contentType:false,
                data:datos,
                processData:false,
                cache:false,
                beforeSend: function(){
                    $("#archivodab").prop('disabled', true);
                    $("#enviardab").prop('disabled', true);
                    $("#respuestadab").html($('<br><span class="mensaje">Subiendo archivo...</span><br>'));
                },
                success: function(data){
                    if (esDatetime(data) == true) {
                        $("#formulariodab").load('action/tabla.php',{fecha:data, opt:$('#optdab').val()});
                        $("#respuestadab").html('');
                    } else {
                        $("#respuestadab").html($('<br><span class="mensaje">'+data+'</span><br>'));
                    }
                }
            });
        }
    });
});


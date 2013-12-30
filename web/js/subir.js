/*
 * @author Marcelo Heredia
 * Dec, 2013
*/
$(function () {
    $('#enviar').click(function(){
        var error = '';
        if ($('#archivo').val().length < 1) {
            error += 'No selecciono ningun archivo';
        } else {
            if (verExtencion($('#archivo')[0].files[0].name) == false) {
                error += 'No es un archivo excel';
            }
        }
        if (error != '') {
            $("#respuesta").html($('<br><span class="mensaje">'+error+'</span><br>'));
        } else {
            var archivos = $('#archivo');
            var archivo = archivos[0].files[0];
            var datos = new FormData();
            datos.append('archivo',archivo);
            datos.append('opt',$('#opt').val());
            var url = 'client/subir.php';
            $.ajax({
                url:url,
                type:'POST',
                contentType:false,
                data:datos,
                processData:false,
                cache:false,
                beforeSend: function(){
                    $("#archivo").prop('disabled', true);
                    $("#enviar").prop('disabled', true);
                    $("#respuesta").html($('<br><span class="mensaje">Subiendo archivo...</span><br>'));
                },
                success: function(data){
                    if (esDatetime(data) == true) {
                        $("#formulario").load('action/tabla.php',{fecha:data, opt:$('#opt').val()});
                        $("#respuesta").html('');
                    } else {
                        $("#respuesta").html($('<br><span class="mensaje">'+data+'</span><br>'));
                    } 
                }
            });
        }
    });
});


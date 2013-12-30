/*
 * @author Marcelo Heredia
 * Dec, 2013
*/
$(function () {
    if (typeof($_GET['fecha']) != "undefined" && $_GET['fecha'] !== null) {
        if (typeof($_GET['opt']) != "undefined" && $_GET['opt'] !== null) {
            var datos = new FormData();
            datos.append('fecha',$_GET['fecha']);
            datos.append('opt',$_GET['opt']);
            $.ajax({
                url:'../client/tabla.php',
                type:'POST',
                contentType:false,
                data:datos,
                processData:false,
                cache:false,
                beforeSend: function(){
                    $("#tabla").html($('<br><span class="mensaje">Cargando datos...</span><br>'));
                },
                success: function(data){
                     $("#tabla").html(data);
                     $('table').tablePagination({
                        rowsPerPage : 5
                     });
                }
            });
        } else {
            $("#tabla").html($('<br><span class="mensaje">Hubo un error, favor vuelva a intentarlo</span><br>'));
        }
    } else {
        $("#tabla").html($('<br><span class="mensaje">Hubo un error, favor vuelva a intentarlo</span><br>'));
    }
});


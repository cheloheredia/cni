/*
 * @author Marcelo Heredia
 * Dec, 2013
*/

/*
 * @var string
 */
var buscarautocompletes = 'client/buscarautocompletes.php';

/*
 * @var string
 */
var clienteprospecto = 'client/clienteprospecto.php';

function esEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email)) {
       return false;
    }else{
       return true;
    }
}

function obtenerVariablesGet(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;
    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
    }
    return params;
}

function verExtencion(nombre) {
    extencion = nombre.substring(nombre.lastIndexOf('.') + 1);
    var error = '';
    if (extencion == 'xls' || extencion == 'xlsx') {
        error = 'es';
    }
    if(error == 'es') {
        return true;
    } else {
        return false;
    }
}

function esDatetime(fecha){
    var  ExpRegDate = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/;
    var res = true;
    if (!ExpRegDate.test(fecha)) {
        res = false;
    }
    return res;
}

function itemMarcado(event, ui)
{
    var item = ui.item.label;
    switch(ui.item.value.opt){
        case 'recinto':
            $("#recinto").val(item);
            break;
    }
    event.preventDefault();
}

function itemSeleccionado(event, ui)
{
    var item = ui.item.label;
    switch(ui.item.value.opt){
        case 'recinto':
            $("#recinto").val(item);
            $("#recinto").prop('disabled', true);
            break;
    }
    event.preventDefault();
}
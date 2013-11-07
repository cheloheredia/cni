<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Planificacion Despacho</title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css" media="all" /> 
    <link href="css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <!--[if IE]>
		<link rel="stylesheet" href="ie.css" type="text/css" media="all">
	<![endif]-->
    <script type="text/javascript">
	$(function(){
			 $("#tabs" ).tabs();
	});
	</script>
</head>

<body>
    <div id="tabs">
        <ul style="font-size:12px;">
            <li><a href="#tabs-1">CARGAR EXCEL</a></li>
        </ul>
	<div id="tabs-1" style="background:#FFF;">
    
     <div id="contenido" >
                                  <form action="action/planificacion.php" method="post" enctype="multipart/form-data">
                          <br />
                          <br />
                          <table width="635" border="0" cellspacing="0" cellpadding="0" style="border:2px #FF9933 solid;margin:0 auto 0 auto;">
                              <tr>
                                <td width="241">&nbsp;</td>
                                <td width="394">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="241">&nbsp;</td>
                                <td width="394">PLANIFICACION DESPACHO</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><input name="archivo" type="file" size="35" /></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td> <input name="enviar" type="submit" value="Subir Archivo" /></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                        </table> 
                          <input name="action" type="hidden" value="upload" />     
                            </form>
                            </div>                     
    
    </div>
</div>
    <div id="dialog"></div> 
</body>
</html>


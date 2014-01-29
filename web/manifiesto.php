<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Manifiesto Maritimo</title>
	<script src="lib/jquery-1.10.2.min.js"></script>
  <script src="js/ini.js"></script>
  <script src="js/subir.js"></script>
</head>
<body>
    <div border="0" id='formulario' style="border:2px #FF9933 solid; overflow-x:scroll;">
      <label for="archivo">Archivo Manifiesto Maritimo:</label>
      <input name="archivo" id="archivo" type="file" size="35" style='padding-left: auto; padding-right: auto;'/><br>
      <input name="opt" id='opt' type="hidden" value="manifiesto" />
      <input name="enviar" id='enviar' type="button" value="Subir Archivo" /><br>
    </div>
    <div id="respuesta"></div>
</body>
</html>


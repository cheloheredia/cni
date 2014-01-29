<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DAB</title>
    <script src="lib/jquery-1.10.2.min.js"></script>
    <script src="lib/jquery-ui.min.js"></script>
  <script src="js/ini.js"></script>
  <script src="js/dab.js"></script>
</head>
<body>
    <div border="0" id='formulario' style="border:2px #FF9933 solid; overflow-x:scroll;">
      <label for="recinto">Recinto:</label>
      <input type="text" name="recinto" id="recinto" /><br>
      <label for="archivodab">Archivo DAB:</label>
      <input name="archivodab" id="archivodab" type="file"/><br>
      <input name="optdab" id='optdab' type="hidden" value="dab" />
      <input name="enviardab" id='enviardab' type="button" value="Subir Archivo" /><br>
    </div>
    <div id="respuestadab"></div>
</body>
</html>


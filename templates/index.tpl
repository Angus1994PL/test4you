<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Example usage of VIRGO API PHP</title>
	<script type="text/javascript" src="js/scripts.js" ></script>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">{$ajax}</script>
</head>
<body>
<form action="index.php" method="post" id="frmMain">
	<input type="hidden" name="hidAction" id="hidAction" />	
	
	<div class="dvMain">
				
		<h3>Wybierz moduł:</h3>
		<a href="index_o.php">OFERTY</a> <br />
		<a href="index_i.php">INWESTYCJE</a>
	</div>

	{$synchronizeDB}
</form>
</body>
</html>
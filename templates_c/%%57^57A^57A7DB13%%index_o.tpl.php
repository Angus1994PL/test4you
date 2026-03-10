<?php /* Smarty version 2.6.26, created on 2018-12-17 12:56:47
         compiled from index_o.tpl */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="UTF-8" <?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Example usage of VIRGO API PHP</title>
	<script type="text/javascript" src="js/scripts.js" ></script>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript"><?php echo $this->_tpl_vars['ajax']; ?>
</script>
	<?php if ($this->_tpl_vars['ShowSWF'] ?? false): ?><base id="BaseLink" href="http://<?php echo $this->_tpl_vars['photo']->GetBaseLink(); ?>
"></base><?php endif; ?>
</head>
<body>
<form action="index_o.php" method="post" id="frmMain">
	<input type="hidden" name="hidAction" id="hidAction" />	
	
	<div class="dvMain">
		<?php if (! ($this->_tpl_vars['ShowPhoto'] ?? null)): ?>
            <div>
                <div style="float: left; width: 150px; height: 20px;">Przejdź do <a href="index_i.php">inwestycji</a></div>
                <div style="float: left;">Wybierz język:
                    <?php $_from = $this->_tpl_vars['Languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lng']):
?>
                    <input type="radio" id="lng_<?php echo $this->_tpl_vars['lng']->GetId(); ?>
" value="<?php echo $this->_tpl_vars['lng']->GetId(); ?>
" name="lng" <?php if ($this->_tpl_vars['Lng'] == $this->_tpl_vars['lng']->GetId()): ?>checked="true"<?php endif; ?> onchange="window.location = 'index_o.php?lng=<?php echo $this->_tpl_vars['lng']->GetId(); ?>
';" /><label for="lng_<?php echo $this->_tpl_vars['lng']->GetId(); ?>
"><?php echo $this->_tpl_vars['lng']->GetName(); ?>
</label>
                    <?php endforeach; endif; unset($_from); ?>
                </div>
                <div style="float: left; margin-left: 20px;">
                    <a href="<?php echo $this->_tpl_vars['ApiObj']->GetContactFormAddress(); ?>
" target="_blank">Formularz kontaktowy</a>&nbsp;|&nbsp;
                    <a href="<?php echo $this->_tpl_vars['ApiObj']->GetNewOfferFormAddress(); ?>
" target="_blank">Zgłoś ofertę</a>&nbsp;|&nbsp;
                    <a href="<?php echo $this->_tpl_vars['ApiObj']->GetNewSearchFormAddress(); ?>
" target="_blank">Zgłoś poszukiwanie</a>
                    <?php if ($this->_tpl_vars['ShowOfferDetails'] ?? false): ?>&nbsp;|&nbsp;<a href="<?php echo $this->_tpl_vars['ApiObj']->GetContactPerOfferFormAddress($this->_tpl_vars['offer']->GetId()); ?>
" target="_blank">Kontakt do oferty</a><?php endif; ?>
                </div>
                <div style="float: left; margin-left: 50px;">
                    Newsletter, podaj email: <input type="text" name="nlEmail" maxlength="100" />
                    <input type="button" value="Dodaj" onclick="DoPostBack('newsLetterAdd', '', '')"/>
                    <input type="button" value="Usuń" onclick="DoPostBack('newsLetterDel', '', '')"/>
                </div>
            </div><hr style="clear: both;" />
            <?php if ($this->_tpl_vars['infoMsg'] ?? false): ?><div class="dvInfo"><?php echo $this->_tpl_vars['infoMsg']; ?>
</div><?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowSearchForm'] ?? null): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "search.tpl", 'smarty_include_vars' => array('lng' => $this->_tpl_vars['lng'] ?? null)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowSpecialOffers'] ?? null): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowOffersList'] ?? null): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "offers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowOfferDetails'] ?? null): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "offer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowPhoto'] ?? null): ?>
			<img src="<?php echo $this->_tpl_vars['photo']->GetImgSrc('710_520',true,true); ?>
" onclick="window.close()" style="cursor: pointer;" id="fotoID" />
			<script type="text/javascript">setTimeout('setTimeout("Chsize()",100)', 100);</script>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowSWF'] ?? null): ?>
			<object type="application/x-shockwave-flash" data="<?php echo $this->_tpl_vars['photo']->GetSWFSrc(); ?>
" width="544" height="470">
				<param name="movie" value="<?php echo $this->_tpl_vars['photo']->GetSWFSrc(); ?>
" />
				<param name="wmode" value="transparent" />
				<param name="allowFullScreen" value="true" />
			</object>  
		<?php endif; ?>
	</div>

	<?php echo $this->_tpl_vars['synchronizeDB']; ?>

</form>
</body>
</html>
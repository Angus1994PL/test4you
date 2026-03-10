<?php /* Smarty version 2.6.26, created on 2018-12-18 10:33:07
         compiled from index_i.tpl */ ?>
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
</head>
<body>
<form action="index_i.php" method="post" id="frmMain">
	<input type="hidden" name="hidAction" id="hidAction" />	
	
	<div class="dvMain">
		
		<?php if (! ($this->_tpl_vars['ShowPhoto'] ?? false)): ?>
            <div>
                <div style="float: left; width: 200px; height: 20px;">Przejdź do <a href="index_o.php">ofert</a></div>
                <div style="float: left;">Wybierz język:
                    <?php $_from = $this->_tpl_vars['Languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lng']):
?>
                    <input type="radio" id="lng_<?php echo $this->_tpl_vars['lng']->GetId(); ?>
" value="<?php echo $this->_tpl_vars['lng']->GetId(); ?>
" name="lng" <?php if ($this->_tpl_vars['Lng'] == $this->_tpl_vars['lng']->GetId()): ?>checked="true"<?php endif; ?> onchange="window.location = 'index_i.php?lng=<?php echo $this->_tpl_vars['lng']->GetId(); ?>
';" /><label for="lng_<?php echo $this->_tpl_vars['lng']->GetId(); ?>
"><?php echo $this->_tpl_vars['lng']->GetName(); ?>
</label>
                    <?php endforeach; endif; unset($_from); ?>
                </div>
            </div><hr style="clear: both;" />
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowSearchForm']): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "search_i.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowInvestmentsList'] ?? null): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "investments.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowInvestmentDetails'] ?? null): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "investment.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ShowPhoto'] ?? null): ?>
			<img src="<?php echo $this->_tpl_vars['photo']->GetImgSrc('640_480',true,true); ?>
" onclick="window.close()" style="cursor: pointer;" id="fotoID" />
			<script type="text/javascript">setTimeout('setTimeout("Chsize()",100)', 100);</script>
		<?php endif; ?>		
	</div>

	<?php echo $this->_tpl_vars['synchronizeDB']; ?>

</form>
</body>
</html>
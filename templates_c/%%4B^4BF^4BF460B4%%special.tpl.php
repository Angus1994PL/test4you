<?php /* Smarty version 2.6.26, created on 2018-12-17 12:56:47
         compiled from special.tpl */ ?>
<div class="dvOffersSpecial">
	<table class="tbList clear">
		<caption>Lista ofert specjalnych</caption>
		<tr><td colspan="5"><hr /></td></tr>	
		<?php $_from = $this->_tpl_vars['specialOffers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['offer']):
?>
			<tr>
				<td class="img" rowspan="4"><a href="index_o.php?action=offer&id=<?php echo $this->_tpl_vars['offer']->GetId(); ?>
&lng=<?php echo $this->_tpl_vars['offer']->GetIdLng(); ?>
"><?php echo $this->_tpl_vars['offer']->GetThumbnail(); ?>
</a></td>
				<td class="tit <?php if ($this->_tpl_vars['offer']->GetStatus() <> 'Aktualna'): ?>gray<?php endif; ?>" colspan="2"><?php echo $this->_tpl_vars['offer']->GetSymbol(); ?>
 | <?php echo $this->_tpl_vars['offer']->GetShortDescription(); ?>
</td>
			</tr>
			<tr>
				<td class="key">Lokalizacja:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetLocation(); ?>
</td>
			</tr>
			<tr>
				<td class="key">Powierzchnia:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetArea(); ?>
</td>
			</tr>			
			<tr>
				<td class="key">Cena:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetPrice(); ?>
</td>
			</tr>			
			<tr><td colspan="3"><hr /></td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
</div>
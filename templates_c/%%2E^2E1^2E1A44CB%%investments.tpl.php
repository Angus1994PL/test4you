<?php /* Smarty version 2.6.26, created on 2018-12-18 10:33:11
         compiled from investments.tpl */ ?>
<div class="dvOffers clear">
	<input type="hidden" name="hidPage" id="hidPage" value="<?php echo $this->_tpl_vars['page']; ?>
" />	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "paging.tpl", 'smarty_include_vars' => array('args' => $this->_tpl_vars['args'],'hidId' => 'hidPage','argument' => 'page')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<table class="tbList clear">
		<caption>Lista wyszukanych inwestycji</caption>
		<tr>
			<td class="sort" colspan="5">	
				<input type="hidden" name="hidSort" id="hidSort" value="<?php echo $this->_tpl_vars['sort'] ?? null; ?>
" />		
				<span>Miejscowość <img src="images/sort_up<?php if ($this->_tpl_vars['sort'] ?? null == 'L1'): ?>_active<?php endif; ?>.png" onclick="DoPostBack('sort', 'hidSort', 'L1')" />
					<img src="images/sort_down<?php if ($this->_tpl_vars['sort'] ?? null == 'L2'): ?>_active<?php endif; ?>.png" onclick="DoPostBack('sort', 'hidSort', 'L2')" /></span>
				<span>Cena <input type="image" src="images/sort_up<?php if ($this->_tpl_vars['sort'] ?? null == 'P1'): ?>_active<?php endif; ?>.png" onclick="DoPostBack('sort', 'hidSort', 'P1')" />
					<img src="images/sort_down<?php if ($this->_tpl_vars['sort'] ?? null == 'P2'): ?>_active<?php endif; ?>.png" onclick="DoPostBack('sort', 'hidSort', 'P2')" /></span>
				<span>Powierzchnia <input type="image" src="images/sort_up<?php if ($this->_tpl_vars['sort'] ?? null == 'A1'): ?>_active<?php endif; ?>.png" onclick="DoPostBack('sort', 'hidSort', 'A1')" />
					<img src="images/sort_down<?php if ($this->_tpl_vars['sort'] ?? null == 'A2'): ?>_active<?php endif; ?>.png" onclick="DoPostBack('sort', 'hidSort', 'A2')" /></span>
			</td>
		</tr>
		<tr><td colspan="5"><hr /></td></tr>	
		<?php $_from = $this->_tpl_vars['investments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['invest']):
?>
			<tr>
				<td class="img" rowspan="4"><a href="index_i.php?action=invest&id=<?php echo $this->_tpl_vars['invest']->GetId(); ?>
"><?php echo $this->_tpl_vars['invest']->GetThumbnail(); ?>
</a></td>
				<td class="tit" colspan="2"><?php echo $this->_tpl_vars['invest']->GetNumber(); ?>
 | <?php echo $this->_tpl_vars['invest']->GetName(); ?>
</td>				
				<td class="key">Pokoje:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetRoomsNoFrom(); ?>
 - <?php echo $this->_tpl_vars['invest']->GetRoomsNoTo(); ?>
</td>
			</tr>
			<tr>
				<td class="key">Lokalizacja:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetLocation(); ?>
</td>
				<td class="key">Metraż:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetAreaFrom(); ?>
 - <?php echo $this->_tpl_vars['invest']->GetAreaTo(); ?>
</td>
			</tr>
			<tr>
				<td class="key">Dzielnica:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetQuarter(); ?>
</td>
				<td class="key">Piętra:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetFloorFrom(); ?>
 - <?php echo $this->_tpl_vars['invest']->GetFloorTo(); ?>
</td>
			</tr>
			<tr>
				<td class="key">Ulica:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetStreet(); ?>
</td>
				<td class="key">Cena:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetPriceFrom(); ?>
 - <?php echo $this->_tpl_vars['invest']->GetPriceTo(); ?>
</td>
			</tr>
			<tr>
				<td><a href="index_i.php?action=invest&id=<?php echo $this->_tpl_vars['invest']->GetId(); ?>
">Pokaż szczegóły</a></td>
				<td class="key">Liczba budynków:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetBuildingsCount(); ?>
</td>
				<td class="key">Liczba ofert:</td>
				<td class="val"><?php echo $this->_tpl_vars['invest']->GetOffersCount(); ?>
</td>
			</tr>
			<tr><td colspan="5"><hr /></td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "paging.tpl", 'smarty_include_vars' => array('args' => $this->_tpl_vars['args'],'hidId' => 'hidPage','argument' => 'page')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php /* Smarty version 2.6.26, created on 2018-12-18 10:30:40
         compiled from offers.tpl */ ?>
<div class="dvOffers clear">
	<input type="hidden" name="hidPage" id="hidPage" value="<?php echo $this->_tpl_vars['page']; ?>
" />	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "paging.tpl", 'smarty_include_vars' => array('args' => $this->_tpl_vars['args'],'hidId' => 'hidPage','argument' => 'page')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<table class="tbList clear">
		<caption>Lista wyszukanych ofert</caption>
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
		<?php $_from = $this->_tpl_vars['offers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['offer']):
?>
			<tr>
				<td class="img" rowspan="4"><a href="index_o.php?action=offer&id=<?php echo $this->_tpl_vars['offer']->GetId(); ?>
&lng=<?php echo $this->_tpl_vars['offer']->GetIdLng(); ?>
"><?php if ($this->_tpl_vars['offer']->HasSWF()): ?><?php echo $this->_tpl_vars['offer']->GetSWFIntro(); ?>
<?php else: ?><?php echo $this->_tpl_vars['offer']->GetThumbnail(); ?>
<?php endif; ?></a></td>
				<td class="tit <?php if ($this->_tpl_vars['offer']->GetStatus() <> 'Aktualna'): ?>gray<?php endif; ?>" colspan="2"><?php echo $this->_tpl_vars['offer']->GetSymbol(); ?>
 | <?php echo $this->_tpl_vars['offer']->GetShortDescription(); ?>
</td>
				<?php if ($this->_tpl_vars['offer']->GetObject() != 'Dzialka'): ?>
					<td class="key">Ilość pokoi:</td>
					<td class="val"><?php echo $this->_tpl_vars['offer']->GetRoomsNo(); ?>
</td>
				<?php else: ?>
					<td class="key"></td>
					<td class="val"></td>
				<?php endif; ?>
			</tr>
			<tr>
				<td class="key">Lokalizacja:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetLocation(); ?>
</td>
				<td class="key">Powierzchnia:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetArea(); ?>
</td>
			</tr>
			<tr>
				<td class="key">Dzielnica:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetQuarter(); ?>
</td>
				<?php if ($this->_tpl_vars['offer']->GetObject() == 'Mieszkanie' || $this->_tpl_vars['offer']->GetObject() == 'Lokal'): ?>
					<td class="key">Piętro:</td>
					<td class="val"><?php echo $this->_tpl_vars['offer']->GetFloor(); ?>
</td>
				<?php else: ?>
					<td class="key"></td>
					<td class="val"></td>
				<?php endif; ?>
			</tr>
			<tr>
				<?php if ($this->_tpl_vars['offer']->GetObject() == 'Mieszkanie' || $this->_tpl_vars['offer']->GetObject() == 'Lokal'): ?>
					<td class="key">Rodzaj budynku:</td>
					<td class="val"><?php echo $this->_tpl_vars['offer']->GetBuildingType(); ?>
</td>
				<?php elseif ($this->_tpl_vars['offer']->GetObject() == 'Dom'): ?>
					<td class="key">Rodzaj domu:</td>
					<td class="val"><?php echo $this->_tpl_vars['offer']->RodzajDomu; ?>
</td>
				<?php elseif ($this->_tpl_vars['offer']->GetObject() == 'Dzialka'): ?>
					<td class="key">Przeznaczenie działki:</td>
					<td class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['offer']->PrzeznaczenieDzialkiSet); ?>
</td>
				<?php else: ?>
					<td class="key"></td>
					<td class="val"></td>
				<?php endif; ?>
				<td class="key">Cena:</td>
				<td class="val"><?php echo $this->_tpl_vars['offer']->GetPrice(); ?>
</td>
			</tr>
			<tr>
				<td><a href="index_o.php?action=offer&id=<?php echo $this->_tpl_vars['offer']->GetId(); ?>
&lng=<?php echo $this->_tpl_vars['offer']->GetIdLng(); ?>
">Pokaż szczegóły</a></td>
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
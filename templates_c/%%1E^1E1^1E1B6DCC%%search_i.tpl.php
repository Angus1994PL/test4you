<?php /* Smarty version 2.6.26, created on 2018-12-18 10:33:07
         compiled from search_i.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'search_i.tpl', 43, false),)), $this); ?>
<div class="dvSearch">

	<div class="row">Wyszukiwarka inwestycji</div>
	<div class="row">
		<div class="cell c1">
			Kategoria:
		</div>
		<div class="cell right c2">Numer:</div>
		<div class="cell c3"><input type="text" name="txtNumber" size="15" value="<?php echo $this->_tpl_vars['post']['txtNumber'] ?? null; ?>
" /></div>
		<div class="cell right c4">Nazwa:</div>
		<div class="cell c5"><input type="text" name="txtName" size="15" value="<?php echo $this->_tpl_vars['post']['txtName'] ?? null; ?>
"  /></div>		
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbCategories[]" size="3" multiple="multiple">
				<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dest']):
?>
					<option value="<?php echo $this->_tpl_vars['dest']; ?>
" <?php if ($this->_tpl_vars['categorySelected'][$this->_tpl_vars['dest']]): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['dest']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell right c2">Powierzchnia:</div>
		<div class="cell c3"><input type="text" name="txtArea" size="15" value="<?php echo $this->_tpl_vars['post']['txtArea'] ?? null; ?>
"  /></div>
		<div class="cell right c4">Cena:</div>
		<div class="cell c5"><input type="text" name="txtPrice" size="15" value="<?php echo $this->_tpl_vars['post']['txtPrice'] ?? null; ?>
"  /></div>
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbProvince" onchange="ProvinceChanged(this, true, <?php echo $this->_tpl_vars['Lng'] ?? null; ?>
)">
				<option value="-1">wybierz województwo</option>
				<?php $_from = $this->_tpl_vars['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['province']):
?>
					<option value="<?php echo $this->_tpl_vars['province']; ?>
" <?php if ($this->_tpl_vars['post']['cmbProvince'] ?? null == $this->_tpl_vars['province'] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['province']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell right c2">Pokoje:</div>
		<div class="cell c3"><input type="text" name="txtRoom" size="15" value="<?php echo $this->_tpl_vars['post']['txtRoom'] ?? null; ?>
"  /></div>
		<div class="cell right c4">Piętro:</div>
		<div class="cell c5"><input type="text" name="txtFloor" size="15" value="<?php echo $this->_tpl_vars['post']['txtFloor'] ?? null; ?>
"  /></div>		
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select id="cmbDistrict" name="cmbDistrict[]" size="5" multiple="multiple" onchange="DistrictChanged(this, true, <?php echo $this->_tpl_vars['Lng']; ?>
)">
				<?php if (count($this->_tpl_vars['districts'] ?? []) == 0): ?> 
				<option value="-1">wybierz powiat</option>
				<?php endif; ?>
				<?php $_from = ($this->_tpl_vars['districts'] ?? []); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['district']):
?>
					<option value="<?php echo $this->_tpl_vars['district'] ?? null; ?>
" <?php if ($this->_tpl_vars['districtsSelected'][$this->_tpl_vars['district'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo ($this->_tpl_vars['district'] ?? null); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell c23">
			<select id="cmbLocation" name="cmbLocation[]" size="5" multiple="multiple" onchange="LocationChanged(this, true, <?php echo $this->_tpl_vars['Lng']; ?>
)">
				<?php if (count($this->_tpl_vars['locations'] ?? []) == 0): ?>
				<option value="-1">wybierz miasto</option>
				<?php endif; ?>
				<?php $_from = $this->_tpl_vars['locations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
					<option value="<?php echo $this->_tpl_vars['location']; ?>
" <?php if ($this->_tpl_vars['locationsSelected'][$this->_tpl_vars['location'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['location']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell c456">
			<select id="cmbQuarter" name="cmbQuarter[]" size="5" multiple="multiple">
				<?php if (count($this->_tpl_vars['quarters'] ?? []) == 0): ?>
				<option value="-1">wybierz dzielnicę</option>
				<?php endif; ?>
				<?php $_from = $this->_tpl_vars['quarters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['quarter']):
?>
					<option value="<?php echo $this->_tpl_vars['quarter']; ?>
" <?php if ($this->_tpl_vars['quartersSelected'][$this->_tpl_vars['quarter'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['quarter'] ?? null; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
	</div>	
	<div class="row clear">
		<div class="cell"><input type="button" value="Szukaj" onclick="DoPostBack('search', '', '')"/></div>
	</div>

</div>
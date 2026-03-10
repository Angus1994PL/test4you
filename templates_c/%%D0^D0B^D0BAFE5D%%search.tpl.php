<?php /* Smarty version 2.6.26, created on 2018-12-17 12:56:47
         compiled from search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'search.tpl', 49, false),)), $this); ?>
<div class="dvSearch">

	<div class="row">Wyszukiwarka ofert</div>
	<div class="row">
		<div class="cell c1">
			<select name="cmbType">
				<option value="0" <?php if ($this->_tpl_vars['post']['cmbType'] ?? null == 0): ?>selected="selected"<?php endif; ?>>sprzedaż</option>
				<option value="1" <?php if ($this->_tpl_vars['post']['cmbType'] ?? null == 1): ?>selected="selected"<?php endif; ?>>wynajem</option>				
			</select>
		</div>
		<div class="cell right c2">Cena od:</div>
		<div class="cell c3"><input type="text" name="txtPriceFrom" size="15" value="<?php echo $this->_tpl_vars['post']['txtPriceFrom'] ?? ''; ?>
" /></div>
		<div class="cell right c4">do:</div>
		<div class="cell c5"><input type="text" name="txtPriceTo" size="15" value="<?php echo $this->_tpl_vars['post']['txtPriceTo'] ?? ''; ?>
"  /></div>
		<div class="cell left c6">zł</div>
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbObject" onchange="ObjectChange(this)">
				<option value="-1">wybierz przedmiot</option>
				<?php $_from = $this->_tpl_vars['objects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['obj'] => $this->_tpl_vars['object']):
?>
					<option value="<?php echo $this->_tpl_vars['obj']; ?>
" <?php if ($this->_tpl_vars['post']['cmbObject'] == $this->_tpl_vars['obj']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['object']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell right c2">Metraż od:</div>
		<div class="cell c3"><input type="text" name="txtAreaFrom" size="15" value="<?php echo $this->_tpl_vars['post']['txtAreaFrom'] ?? ''; ?>
"  /></div>
		<div class="cell right c4">do:</div>
		<div class="cell c5"><input type="text" name="txtAreaTo" size="15" value="<?php echo $this->_tpl_vars['post']['txtAreaTo'] ?? ''; ?>
"  /></div>
		<div class="cell left c6">m<sup>2</sup></div>
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbProvince" onchange="ProvinceChanged(this, false, <?php echo $this->_tpl_vars['Lng']; ?>
)">
				<option value="-1">wybierz województwo</option>
				<?php $_from = $this->_tpl_vars['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['province']):
?>
					<option value="<?php echo $this->_tpl_vars['province']; ?>
" <?php if ($this->_tpl_vars['post']['cmbProvince'] ?? null == $this->_tpl_vars['province'] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['province'] ?? null; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell right c2">Pokoje od:</div>
		<div class="cell c3"><input type="text" name="txtRoomsFrom" size="15" value="<?php echo $this->_tpl_vars['post']['txtRoomsFrom'] ?? null; ?>
"  /></div>
		<div class="cell right c4">do:</div>
		<div class="cell c5"><input type="text" name="txtRoomsTo" size="15" value="<?php echo $this->_tpl_vars['post']['txtRoomsTo'] ?? null; ?>
"  /></div>		
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select id="cmbDistrict" name="cmbDistrict[]" size="5" multiple="multiple" onchange="DistrictChanged(this, false, <?php echo $this->_tpl_vars['Lng']; ?>
)">
				<?php if (count($this->_tpl_vars['districts'] ?? []) == 0): ?> 
				<option value="-1">wybierz powiat</option>
				<?php endif; ?>
				<?php $_from = $this->_tpl_vars['districts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['district']):
?>
					<option value="<?php echo $this->_tpl_vars['district']; ?>
" <?php if ($this->_tpl_vars['districtsSelected'][$this->_tpl_vars['district'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['district'] ?? null; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<div class="cell c23">
			<select id="cmbLocation" name="cmbLocation[]" size="5" multiple="multiple" onchange="LocationChanged(this, false, <?php echo $this->_tpl_vars['Lng']; ?>
)">
				<?php if (count($this->_tpl_vars['locations'] ?? []) == 0): ?>
				<option value="-1">wybierz miasto</option>
				<?php endif; ?>
				<?php $_from = $this->_tpl_vars['locations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
					<option value="<?php echo $this->_tpl_vars['location']; ?>
" <?php if ($this->_tpl_vars['locationsSelected'][$this->_tpl_vars['location'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['location'] ?? null; ?>
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
        
    	
    <div class="row clear" id="dvFlatType" <?php if ($this->_tpl_vars['showFlatTypes'] ?? null == false): ?> style="display: none;" <?php endif; ?>>
		<div class="cell c1">Rodzaj mieszkania:</div>
		<div class="cell c23">
			<select name="cmbFlatType[]" size="5" multiple="multiple">
				<?php $_from = $this->_tpl_vars['flatTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
					<option value="<?php echo $this->_tpl_vars['type']; ?>
" <?php if ($this->_tpl_vars['flatTypesSelected'][$this->_tpl_vars['type'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['type']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
	</div>
	<div class="row clear" id="dvHouseType" <?php if ($this->_tpl_vars['showHouseTypes'] ?? null == false): ?> style="display: none;" <?php endif; ?>>
		<div class="cell c1">Rodzaj domu:</div>
		<div class="cell c23">
			<select name="cmbHouseType[]" size="5" multiple="multiple">
				<?php $_from = $this->_tpl_vars['houseTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
					<option value="<?php echo $this->_tpl_vars['type']; ?>
" <?php if ($this->_tpl_vars['houseTypesSelected'][$this->_tpl_vars['type'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['type']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
	</div>
	<div class="row clear" id="dvFieldDestiny" <?php if ($this->_tpl_vars['showFieldDestiny'] ?? null == false): ?> style="display: none;" <?php endif; ?>>
		<div class="cell c1">Przeznaczenie działki:</div>
		<div class="cell c23">
			<select name="cmbFieldDestiny[]" size="5" multiple="multiple">
				<?php $_from = $this->_tpl_vars['fieldDestiny']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dest']):
?>
					<option value="<?php echo $this->_tpl_vars['dest']; ?>
" <?php if ($this->_tpl_vars['fieldDestinySelected'][$this->_tpl_vars['dest'] ?? null] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['dest']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
	</div>
	<div class="row clear" id="dvLocalDestiny" <?php if ($this->_tpl_vars['showLocalDestiny'] ?? null == false): ?> style="display: none;" <?php endif; ?>>
		<div class="cell c1">Przeznaczenie lokalu:</div>
		<div class="cell c23">
			<select name="cmbLocalDestiny[]" size="5" multiple="multiple">
				<?php $_from = $this->_tpl_vars['localDestiny']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dest']):
?>
					<option value="<?php echo $this->_tpl_vars['dest']; ?>
" <?php if ($this->_tpl_vars['localDestinySelected'][$this->_tpl_vars['dest']] ?? null): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['dest']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
	</div>
    
	<div class="row clear">
		<div class="cell"><input type="button" value="Szukaj" onclick="DoPostBack('search', '', '')"/></div>
		<div class="cell"><input type="checkbox" value="1" name="cbxSWF" id="cbxSWF" <?php if ($this->_tpl_vars['post']['cbxSWF'] ?? null): ?>checked="checked"<?php endif; ?> /><label for="cbxSWF">Pokaż tylko Wirtualne wizyty</label></div>
	</div>

</div>
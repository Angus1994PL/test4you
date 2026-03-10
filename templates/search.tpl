<div class="dvSearch">

	<div class="row">Wyszukiwarka ofert</div>
	<div class="row">
		<div class="cell c1">
			<select name="cmbType">
				<option value="0" {if $post.cmbType == 0}selected="selected"{/if}>sprzedaż</option>
				<option value="1" {if $post.cmbType == 1}selected="selected"{/if}>wynajem</option>				
			</select>
		</div>
		<div class="cell right c2">Cena od:</div>
		<div class="cell c3"><input type="text" name="txtPriceFrom" size="15" value="{$post.txtPriceFrom}" /></div>
		<div class="cell right c4">do:</div>
		<div class="cell c5"><input type="text" name="txtPriceTo" size="15" value="{$post.txtPriceTo}"  /></div>
		<div class="cell left c6">zł</div>
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbObject" onchange="ObjectChange(this)">
				<option value="-1">wybierz przedmiot</option>
				{foreach from=$objects item=object key=obj}
					<option value="{$obj}" {if $post.cmbObject == $obj}selected="selected"{/if}>{$object}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell right c2">Metraż od:</div>
		<div class="cell c3"><input type="text" name="txtAreaFrom" size="15" value="{$post.txtAreaFrom}"  /></div>
		<div class="cell right c4">do:</div>
		<div class="cell c5"><input type="text" name="txtAreaTo" size="15" value="{$post.txtAreaTo}"  /></div>
		<div class="cell left c6">m<sup>2</sup></div>
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbProvince" onchange="ProvinceChanged(this, false, {$Lng})">
				<option value="-1">wybierz województwo</option>
				{foreach from=$provinces item=province}
					<option value="{$province}" {if $post.cmbProvince == $province}selected="selected"{/if}>{$province}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell right c2">Pokoje od:</div>
		<div class="cell c3"><input type="text" name="txtRoomsFrom" size="15" value="{$post.txtRoomsFrom}"  /></div>
		<div class="cell right c4">do:</div>
		<div class="cell c5"><input type="text" name="txtRoomsTo" size="15" value="{$post.txtRoomsTo}"  /></div>		
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select id="cmbDistrict" name="cmbDistrict[]" size="5" multiple="multiple" onchange="DistrictChanged(this, false, {$Lng})">
				{if $districts|@count == 0} 
				<option value="-1">wybierz powiat</option>
				{/if}
				{foreach from=$districts item=district}
					<option value="{$district}" {if $districtsSelected.$district}selected="selected"{/if}>{$district}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell c23">
			<select id="cmbLocation" name="cmbLocation[]" size="5" multiple="multiple" onchange="LocationChanged(this, false, {$Lng})">
				{if $locations|@count == 0}
				<option value="-1">wybierz miasto</option>
				{/if}
				{foreach from=$locations item=location}
					<option value="{$location}" {if $locationsSelected.$location}selected="selected"{/if}>{$location}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell c456">
			<select id="cmbQuarter" name="cmbQuarter[]" size="5" multiple="multiple">
				{if $quarters|@count == 0}
				<option value="-1">wybierz dzielnicę</option>
				{/if}
				{foreach from=$quarters item=quarter}
					<option value="{$quarter}" {if $quartersSelected.$quarter}selected="selected"{/if}>{$quarter}</option>
				{/foreach}
			</select>
		</div>
	</div>
        
    	
    <div class="row clear" id="dvFlatType" {if $showFlatTypes == false} style="display: none;" {/if}>
		<div class="cell c1">Rodzaj mieszkania:</div>
		<div class="cell c23">
			<select name="cmbFlatType[]" size="5" multiple="multiple">
				{foreach from=$flatTypes item=type}
					<option value="{$type}" {if $flatTypesSelected.$type}selected="selected"{/if}>{$type}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="row clear" id="dvHouseType" {if $showHouseTypes == false} style="display: none;" {/if}>
		<div class="cell c1">Rodzaj domu:</div>
		<div class="cell c23">
			<select name="cmbHouseType[]" size="5" multiple="multiple">
				{foreach from=$houseTypes item=type}
					<option value="{$type}" {if $houseTypesSelected.$type}selected="selected"{/if}>{$type}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="row clear" id="dvFieldDestiny" {if $showFieldDestiny == false} style="display: none;" {/if}>
		<div class="cell c1">Przeznaczenie działki:</div>
		<div class="cell c23">
			<select name="cmbFieldDestiny[]" size="5" multiple="multiple">
				{foreach from=$fieldDestiny item=dest}
					<option value="{$dest}" {if $fieldDestinySelected.$dest}selected="selected"{/if}>{$dest}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="row clear" id="dvLocalDestiny" {if $showLocalDestiny == false} style="display: none;" {/if}>
		<div class="cell c1">Przeznaczenie lokalu:</div>
		<div class="cell c23">
			<select name="cmbLocalDestiny[]" size="5" multiple="multiple">
				{foreach from=$localDestiny item=dest}
					<option value="{$dest}" {if $localDestinySelected.$dest}selected="selected"{/if}>{$dest}</option>
				{/foreach}
			</select>
		</div>
	</div>
    
	<div class="row clear">
		<div class="cell"><input type="button" value="Szukaj" onclick="DoPostBack('search', '', '')"/></div>
		<div class="cell"><input type="checkbox" value="1" name="cbxSWF" id="cbxSWF" {if $post.cbxSWF}checked="checked"{/if} /><label for="cbxSWF">Pokaż tylko Wirtualne wizyty</label></div>
	</div>

</div>
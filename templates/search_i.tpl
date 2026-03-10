<div class="dvSearch">

	<div class="row">Wyszukiwarka inwestycji</div>
	<div class="row">
		<div class="cell c1">
			Kategoria:
		</div>
		<div class="cell right c2">Numer:</div>
		<div class="cell c3"><input type="text" name="txtNumber" size="15" value="{$post.txtNumber}" /></div>
		<div class="cell right c4">Nazwa:</div>
		<div class="cell c5"><input type="text" name="txtName" size="15" value="{$post.txtName}"  /></div>		
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbCategories[]" size="3" multiple="multiple">
				{foreach from=$categories item=dest}
					<option value="{$dest}" {if $categorySelected.$dest}selected="selected"{/if}>{$dest}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell right c2">Powierzchnia:</div>
		<div class="cell c3"><input type="text" name="txtArea" size="15" value="{$post.txtArea}"  /></div>
		<div class="cell right c4">Cena:</div>
		<div class="cell c5"><input type="text" name="txtPrice" size="15" value="{$post.txtPrice}"  /></div>
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select name="cmbProvince" onchange="ProvinceChanged(this, true, {$Lng})">
				<option value="-1">wybierz województwo</option>
				{foreach from=$provinces item=province}
					<option value="{$province}" {if $post.cmbProvince == $province}selected="selected"{/if}>{$province}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell right c2">Pokoje:</div>
		<div class="cell c3"><input type="text" name="txtRoom" size="15" value="{$post.txtRoom}"  /></div>
		<div class="cell right c4">Piętro:</div>
		<div class="cell c5"><input type="text" name="txtFloor" size="15" value="{$post.txtFloor}"  /></div>		
	</div>
	<div class="row clear">
		<div class="cell c1">
			<select id="cmbDistrict" name="cmbDistrict[]" size="5" multiple="multiple" onchange="DistrictChanged(this, true, {$Lng})">
				{if $districts|@count == 0} 
				<option value="-1">wybierz powiat</option>
				{/if}
				{foreach from=$districts item=district}
					<option value="{$district}" {if $districtsSelected.$district}selected="selected"{/if}>{$district}</option>
				{/foreach}
			</select>
		</div>
		<div class="cell c23">
			<select id="cmbLocation" name="cmbLocation[]" size="5" multiple="multiple" onchange="LocationChanged(this, true, {$Lng})">
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
	<div class="row clear">
		<div class="cell"><input type="button" value="Szukaj" onclick="DoPostBack('search', '', '')"/></div>
	</div>

</div>
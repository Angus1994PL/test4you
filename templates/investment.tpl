<div class="dvOffer">
	<h3>{$invest->GetNumber()} | {$invest->GetName()}</h3>
	<div class="section">
		<div class="tit">Informacje ogólne</div>
		<div class="row">
			<div class="key">Lokalizacja</div>
			<div class="val">{$invest->GetAllLocation()}</div>
		</div>
		<div class="row">
			<div class="key">Kategoria</div>
			<div class="val">{$invest->GetCategory()}</div>
		</div>
		<div class="row">
			<div class="key">Metraż</div>
			<div class="val">{$invest->GetAreaFrom()} - {$invest->GetAreaTo()}</div>
		</div>
		<div class="row">
			<div class="key">Piętrz</div>
			<div class="val">{$invest->GetFloorFrom()} - {$invest->GetFloorTo()}</div>
		</div>
		<div class="row">
			<div class="key">Pokoje</div>
			<div class="val">{$invest->GetRoomsNoFrom()} - {$invest->GetRoomsNoTo()}</div>
		</div>
		<div class="row">
			<div class="key">Cena</div>
			<div class="val">{$invest->GetPriceFrom()} - {$invest->GetPriceTo()}</div>
		</div>
        <div class="row">
			<div class="key">Cena m2</div>
			<div class="val">{$invest->GetPricem2From()} - {$invest->GetPricem2To()}</div>
		</div>
		<div class="row">
			<div class="key">Data wprowadzenia</div>
			<div class="val">{$invest->GetCreationDate()}</div>
		</div>
		<div class="row">
			<div class="key">Termin oddania</div>
			<div class="val">{$invest->GetDueDate()}</div>
		</div>
		<div class="row">
			<div class="key">Opis skrócony</div>
			<div class="val">{$invest->GetShortDescription()}</div>
		</div>
		<div class="row">
			<div class="key">Garaż</div>
			<div class="val">{$invest->GetGarage()}</div>
		</div>
		<div class="row">
			<div class="key">Basen</div>
			<div class="val">{$invest->GetPool()}</div>
		</div>
		<div class="row">
			<div class="key">Taras</div>
			<div class="val">{$invest->GetTerrace()}</div>
		</div>
		<div class="row">
			<div class="key">Klimatyzacja</div>
			<div class="val">{$invest->GetAirConditioning()}</div>
		</div>
		<div class="row">
			<div class="key">Specjalna</div>
			<div class="val">{$invest->GetSpecial()}</div>
		</div>
		<div class="row">
			<div class="val2">{$invest->GetDescription()}</div>
		</div>
	</div>
		
	<div class="section">
		<div class="tit">Budynki</div>
		{foreach from=$invest->GetBuildings() item=building}
			<div class="row">
				<div class="key">{$building->GetSymbol()} | {$building->GetName()}</div>
				<div class="val">
					{foreach from=$building->GetOffers() item=offer}
						<a href="index_o.php?action=offer&id={$offer->GetId()}">{$offer->GetSymbol()}</a><br />
					{/foreach}
				</div>
			</div>			
		{/foreach}
	</div>
		
	<div class="section">
		<div class="tit">Galeria zdjęć</div>
		{foreach from=$invest->GetPhotos() item=photo}
			<a href="javascript:ShowPhoto({$photo->GetId()}, '_i')"><img src="{$photo->GetImgSrc('100_75', false, false)}"/></a>
		{/foreach}
	</div>
	
	<div class="section">
		<div class="tit">Mapa</div>
		<div class="row">
			<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAI_u_8fFv2FaLcT97zZyKHBTIE61pJbpvuaVTMuG-iu5z1GsHwRQcfWSPEsjmb8_NS0clDLL5_WLtdA" type="text/javascript" ></script>
			<img src="http://www.google.com/mapfiles/maps_res_logo.gif" style="display:none" onload="LoadMap({$invest->GetMapMarker()})" onunload="GUnload()">
			<div id="mapa" style="width: 450px; height: 320px;"></div>
		</div>
	</div>
	
	<div class="section">
		<div class="tit">Kontakt</div>
		{assign var='dep' value=$invest->GetDepartmentObj()}
		<div class="row">
			<div class="key">Oddział</div>
			<div class="val">{$dep->GetName()}</div>
		</div>
		<div class="row">
			<div class="key">Dane</div>
			<div class="val">{$invest->GetContact()}</div>
		</div>		
	</div>
	<a class="clear" href="javascript:history.back()">wróć do poprzedniej strony</a>
</div>
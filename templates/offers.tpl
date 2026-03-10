<div class="dvOffers clear">
	<input type="hidden" name="hidPage" id="hidPage" value="{$page}" />	
	{include file="paging.tpl" args=$args hidId="hidPage" argument="page"}
	<table class="tbList clear">
		<caption>Lista wyszukanych ofert</caption>
		<tr>
			<td class="sort" colspan="5">	
				<input type="hidden" name="hidSort" id="hidSort" value="{$sort}" />		
				<span>Miejscowość <img src="images/sort_up{if $sort == 'L1'}_active{/if}.png" onclick="DoPostBack('sort', 'hidSort', 'L1')" />
					<img src="images/sort_down{if $sort == 'L2'}_active{/if}.png" onclick="DoPostBack('sort', 'hidSort', 'L2')" /></span>
				<span>Cena <input type="image" src="images/sort_up{if $sort == 'P1'}_active{/if}.png" onclick="DoPostBack('sort', 'hidSort', 'P1')" />
					<img src="images/sort_down{if $sort == 'P2'}_active{/if}.png" onclick="DoPostBack('sort', 'hidSort', 'P2')" /></span>
				<span>Powierzchnia <input type="image" src="images/sort_up{if $sort == 'A1'}_active{/if}.png" onclick="DoPostBack('sort', 'hidSort', 'A1')" />
					<img src="images/sort_down{if $sort == 'A2'}_active{/if}.png" onclick="DoPostBack('sort', 'hidSort', 'A2')" /></span>
			</td>
		</tr>
		<tr><td colspan="5"><hr /></td></tr>
		{foreach from=$offers item=offer}
			<tr>
				<td class="img" rowspan="4"><a href="index_o.php?action=offer&id={$offer->GetId()}&lng={$offer->GetIdLng()}">{if $offer->HasSWF()}{$offer->GetSWFIntro()}{else}{$offer->GetThumbnail()}{/if}</a></td>
				<td class="tit {if $offer->GetStatus() <> 'Aktualna'}gray{/if}" colspan="2">{$offer->GetSymbol()} | {$offer->GetShortDescription()}</td>
				{if $offer->GetObject() != 'Dzialka'}
					<td class="key">Ilość pokoi:</td>
					<td class="val">{$offer->GetRoomsNo()}</td>
				{else}
					<td class="key"></td>
					<td class="val"></td>
				{/if}
			</tr>
			<tr>
				<td class="key">Lokalizacja:</td>
				<td class="val">{$offer->GetLocation()}</td>
				<td class="key">Powierzchnia:</td>
				<td class="val">{$offer->GetArea()}</td>
			</tr>
			<tr>
				<td class="key">Dzielnica:</td>
				<td class="val">{$offer->GetQuarter()}</td>
				{if $offer->GetObject() == 'Mieszkanie' || $offer->GetObject() == 'Lokal'}
					<td class="key">Piętro:</td>
					<td class="val">{$offer->GetFloor()}</td>
				{else}
					<td class="key"></td>
					<td class="val"></td>
				{/if}
			</tr>
			<tr>
				{if $offer->GetObject() == 'Mieszkanie' || $offer->GetObject() == 'Lokal'}
					<td class="key">Rodzaj budynku:</td>
					<td class="val">{$offer->GetBuildingType()}</td>
				{elseif $offer->GetObject() == 'Dom'}
					<td class="key">Rodzaj domu:</td>
					<td class="val">{$offer->RodzajDomu}</td>
				{elseif $offer->GetObject() == 'Dzialka'}
					<td class="key">Przeznaczenie działki:</td>
					<td class="val">{$offer->GetSetAsText($offer->PrzeznaczenieDzialkiSet)}</td>
				{else}
					<td class="key"></td>
					<td class="val"></td>
				{/if}
				<td class="key">Cena:</td>
				<td class="val">{$offer->GetPrice()}</td>
			</tr>
			<tr>
				<td><a href="index_o.php?action=offer&id={$offer->GetId()}&lng={$offer->GetIdLng()}">Pokaż szczegóły</a></td>
			</tr>
			<tr><td colspan="5"><hr /></td></tr>
		{/foreach}
	</table>
	{include file="paging.tpl" args=$args hidId="hidPage" argument="page"}
</div>
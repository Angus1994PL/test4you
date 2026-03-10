<div class="dvOffers clear">
	<input type="hidden" name="hidPage" id="hidPage" value="{$page}" />	
	{include file="paging.tpl" args=$args hidId="hidPage" argument="page"}
	<table class="tbList clear">
		<caption>Lista wyszukanych inwestycji</caption>
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
		{foreach from=$investments item=invest}
			<tr>
				<td class="img" rowspan="4"><a href="index_i.php?action=invest&id={$invest->GetId()}">{$invest->GetThumbnail()}</a></td>
				<td class="tit" colspan="2">{$invest->GetNumber()} | {$invest->GetName()}</td>				
				<td class="key">Pokoje:</td>
				<td class="val">{$invest->GetRoomsNoFrom()} - {$invest->GetRoomsNoTo()}</td>
			</tr>
			<tr>
				<td class="key">Lokalizacja:</td>
				<td class="val">{$invest->GetLocation()}</td>
				<td class="key">Metraż:</td>
				<td class="val">{$invest->GetAreaFrom()} - {$invest->GetAreaTo()}</td>
			</tr>
			<tr>
				<td class="key">Dzielnica:</td>
				<td class="val">{$invest->GetQuarter()}</td>
				<td class="key">Piętra:</td>
				<td class="val">{$invest->GetFloorFrom()} - {$invest->GetFloorTo()}</td>
			</tr>
			<tr>
				<td class="key">Ulica:</td>
				<td class="val">{$invest->GetStreet()}</td>
				<td class="key">Cena:</td>
				<td class="val">{$invest->GetPriceFrom()} - {$invest->GetPriceTo()}</td>
			</tr>
			<tr>
				<td><a href="index_i.php?action=invest&id={$invest->GetId()}">Pokaż szczegóły</a></td>
				<td class="key">Liczba budynków:</td>
				<td class="val">{$invest->GetBuildingsCount()}</td>
				<td class="key">Liczba ofert:</td>
				<td class="val">{$invest->GetOffersCount()}</td>
			</tr>
			<tr><td colspan="5"><hr /></td></tr>
		{/foreach}
	</table>
	{include file="paging.tpl" args=$args hidId="hidPage" argument="page"}
</div>
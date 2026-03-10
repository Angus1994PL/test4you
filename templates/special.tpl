<div class="dvOffersSpecial">
	<table class="tbList clear">
		<caption>Lista ofert specjalnych</caption>
		<tr><td colspan="5"><hr /></td></tr>	
		{foreach from=$specialOffers item=offer}
			<tr>
				<td class="img" rowspan="4"><a href="index_o.php?action=offer&id={$offer->GetId()}&lng={$offer->GetIdLng()}">{$offer->GetThumbnail()}</a></td>
				<td class="tit {if $offer->GetStatus() <> 'Aktualna'}gray{/if}" colspan="2">{$offer->GetSymbol()} | {$offer->GetShortDescription()}</td>
			</tr>
			<tr>
				<td class="key">Lokalizacja:</td>
				<td class="val">{$offer->GetLocation()}</td>
			</tr>
			<tr>
				<td class="key">Powierzchnia:</td>
				<td class="val">{$offer->GetArea()}</td>
			</tr>			
			<tr>
				<td class="key">Cena:</td>
				<td class="val">{$offer->GetPrice()}</td>
			</tr>			
			<tr><td colspan="3"><hr /></td></tr>
		{/foreach}
	</table>
</div>
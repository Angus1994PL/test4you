<div class="dvOffer">
	<h3>{$offer->GetSymbol()} | {$offer->GetShortDescription()}</h3>
	<div class="section">
		<div class="tit">Informacje ogólne</div>
        <div class="row">
			<div class="key">Status</div>
			<div class="val">{$offer->GetStatus()}</div>
		</div>
		<div class="row">
			<div class="key">Lokalizacja</div>
			<div class="val">{$offer->GetAllLocation()}</div>
		</div>
		<div class="row">
			<div class="key">Rodzaj budynku</div>
			<div class="val">{$offer->GetBuildingType()}</div>
		</div>
		<div class="row">
			<div class="key">Pow. całkowita [m2]</div>
			<div class="val">{$offer->GetArea()}</div>
		</div>
		{if $offer->GetFloor() != ''}
		<div class="row">
			<div class="key">Piętro</div>
			<div class="val">{$offer->GetFloor()}</div>
		</div>
		{/if}
		{if $offer->GetRoomsNo() != 0}
		<div class="row">
			<div class="key">Ilość pokoi</div>
			<div class="val">{$offer->GetRoomsNo()}</div>
		</div>
		{/if}
		<div class="row">
			<div class="key">Cena</div>
			<div class="val">{$offer->GetPrice()}</div>
		</div>
		<div class="row">
			<div class="key">Data wprowadzenia</div>
			<div class="val">{$offer->GetCreationDate()}</div>
		</div>
		<div class="row">
			<div class="key">Data aktualizacji</div>
			<div class="val">{$offer->GetModificationDate()}</div>
		</div>
		<div class="row">
			<div class="val2">{$offer->UwagiOpis}</div>
		</div>
	</div>

	<div class="section">
		<div class="tit">Nieruchomość</div>
		{if $offer->Standard != ''}
		<div class="row">
			<div class="key">Standard</div>
			<div class="val">{$offer->Standard}</div>
		</div>
		{/if}
		{if $offer->Kategorie != ''}
		<div class="row">
			<div class="key">Kategorie</div>
			<div class="val">{$offer->GetSetAsText($offer->Kategorie)}</div>
		</div>
		{/if}
		{if $offer->DodatkoweOplatyWCzynszu != ''}
		<div class="row">
			<div class="key">Opłaty w czynszu</div>
			<div class="val">{$offer->GetSetAsText($offer->DodatkoweOplatyWCzynszu)}</div>
		</div>
		{/if}
		{if $offer->DodatkoweOplatyWgLicznikow != ''}
		<div class="row">
			<div class="key">Opłaty wg liczników</div>
			<div class="val">{$offer->GetSetAsText($offer->DodatkoweOplatyWgLicznikow)}</div>
		</div>
		{/if}
		{if $offer->IloscPieter != ''}
		<div class="row">
			<div class="key">Ilość pięter w budynku</div>
			<div class="val">{$offer->IloscPieter}</div>
		</div>
		{/if}
		{if $offer->RodzajMieszkania != ''}
		<div class="row">
			<div class="key">Rodzaj mieszkania</div>
			<div class="val">{$offer->RodzajMieszkania}</div>
		</div>
		{/if}
		{if $offer->PrzeznaczenieDzialkiSet != ''}
		<div class="row">
			<div class="key">Przeznaczenie działki</div>
			<div class="val">{$offer->GetSetAsText($offer->PrzeznaczenieDzialkiSet)}</div>
		</div>
		{/if}
		{if $offer->ZagospodarowanieDzialki != ''}
		<div class="row">
			<div class="key">Zagospodarowanie działki</div>
			<div class="val">{$offer->ZagospodarowanieDzialki}</div>
		</div>
		{/if}
		{if $offer->UksztaltowanieDzialki != ''}
		<div class="row">
			<div class="key">Ukształtowanie działki</div>
			<div class="val">{$offer->UksztaltowanieDzialki}</div>
		</div>
		{/if}
		{if $offer->KsztaltDzialki != ''}
		<div class="row">
			<div class="key">Kształt działki</div>
			<div class="val">{$offer->KsztaltDzialki}</div>
		</div>
		{/if}
		{if $offer->OgrodzenieDzialki != ''}
		<div class="row">
			<div class="key">Ogrodzenie działki</div>
			<div class="val">{$offer->OgrodzenieDzialki}</div>
		</div>
		{/if}
		{if $offer->GetBuildingTechnology() != ''}
		<div class="row">
			<div class="key">Technologia budowlana</div>
			<div class="val">{$offer->GetBuildingTechnology()}</div>
		</div>
		{/if}
		{if $offer->StanLokaluLista != ''}
		<div class="row">
			<div class="key">Stan lokalu</div>
			<div class="val">{$offer->StanLokaluLista}</div>
		</div>
		{/if}
		{if $offer->PrzeznaczenieLokalu != ''}
		<div class="row">
			<div class="key">Przeznaczenie lokalu</div>
			<div class="val">{$offer->GetSetAsText($offer->PrzeznaczenieLokalu)}</div>
		</div>
		{/if}
		{if $offer->Okna != ''}
		<div class="row">
			<div class="key">Okna</div>
			<div class="val">{$offer->Okna}</div>
		</div>
		{/if}
		{if $offer->Instalacje != ''}
		<div class="row">
			<div class="key">Instalacje</div>
			<div class="val">{$offer->Instalacje}</div>
		</div>
		{/if}
		{if $offer->Balkon != ''}
		<div class="row">
			<div class="key">Balkon</div>
			<div class="val">{$offer->Balkon}</div>
		</div>
		{/if}
		{if $offer->IloscBalkonow != ''}
		<div class="row">
			<div class="key">Ilość balkonów</div>
			<div class="val">{$offer->IloscBalkonow}</div>
		</div>
		{/if}
		{if $offer->RodzajDomu != ''}
		<div class="row">
			<div class="key">Rodzaj domu</div>
			<div class="val">{$offer->RodzajDomu}</div>
		</div>
		{/if}
		{if $offer->PokrycieDachu != ''}
		<div class="row">
			<div class="key">Pokrycie dachu</div>
			<div class="val">{$offer->PokrycieDachu}</div>
		</div>
		{/if}
		{if $offer->PozwolenieNaUzytkowanie != ''}
		<div class="row">
			<div class="key">Pozwolenie na użytkowanie</div>
			<div class="val">{$offer->PozwolenieNaUzytkowanie}</div>
		</div>
		{/if}
		{if $offer->PowierzchniaUzytkowa != ''}
		<div class="row">
			<div class="key">Powierzchnia użytkowa</div>
			<div class="val">{$offer->PowierzchniaUzytkowa}</div>
		</div>
		{/if}
		{if $offer->Podpiwniczenie != ''}
		<div class="row">
			<div class="key">Podpiwniczenie</div>
			<div class="val">{$offer->Podpiwniczenie}</div>
		</div>
		{/if}
		{if $offer->StanBudynku != ''}
		<div class="row">
			<div class="key">Stan budynku</div>
			<div class="val">{$offer->StanBudynku}</div>
		</div>
		{/if}
		{if $offer->Garaz != ''}
		<div class="row">
			<div class="key">Garaż</div>
			<div class="val">{$offer->Garaz}</div>
		</div>
		{/if}
		{if $offer->GarazMieszkanie != ''}
		<div class="row">
			<div class="key">Garaż</div>
			<div class="val">{$offer->GarazMieszkanie}</div>
		</div>
		{/if}
		{if $offer->RokBudowy != ''}
		<div class="row">
			<div class="key">Rok budowy</div>
			<div class="val">{$offer->RokBudowy}</div>
		</div>
		{/if}
		{if $offer->PlacZabaw != ''}
		<div class="row">
			<div class="key">Plac zabaw</div>
			<div class="val">{$offer->PlacZabaw}</div>
		</div>
		{/if}
		{if $offer->Gaz != ''}
		<div class="row">
			<div class="key">Gaz</div>
			<div class="val">{$offer->Gaz}</div>
		</div>
		{/if}
		{if $offer->Woda != ''}
		<div class="row">
			<div class="key">Woda</div>
			<div class="val">{$offer->Woda}</div>
		</div>
		{/if}
		{if $offer->Kanalizacja != ''}
		<div class="row">
			<div class="key">Kanalizacja</div>
			<div class="val">{$offer->Kanalizacja}</div>
		</div>
		{/if}
		{if $offer->Prad != ''}
		<div class="row">
			<div class="key">Prąd</div>
			<div class="val">{$offer->Prad}</div>
		</div>
		{/if}
		{if $offer->Dojazd != ''}
		<div class="row">
			<div class="key">Dojazd</div>
			<div class="val">{$offer->Dojazd}</div>
		</div>
		{/if}
		{if $offer->Ogrzewanie != ''}
		<div class="row">
			<div class="key">Ogrzewanie</div>
			<div class="val">{$offer->Ogrzewanie}</div>
		</div>
		{/if}
		{if $offer->OdlegloscKomunikacja != ''}
		<div class="row">
			<div class="key">Odleglość od komunikacji [m]</div>
			<div class="val">{$offer->OdlegloscKomunikacja}</div>
		</div>
		{/if}
		{if $offer->OdlegloscOdCentrum != ''}
		<div class="row">
			<div class="key">Odleglość od centrum [m]</div>
			<div class="val">{$offer->OdlegloscOdCentrum}</div>
		</div>
		{/if}
		{if $offer->OdlegloscPrzedszkole != ''}
		<div class="row">
			<div class="key">Odleglość od przedszkola [m]</div>
			<div class="val">{$offer->OdlegloscPrzedszkole}</div>
		</div>
		{/if}
		{if $offer->OdlegloscSklep != ''}
		<div class="row">
			<div class="key">Odleglość od sklepu [m]</div>
			<div class="val">{$offer->OdlegloscSklep}</div>
		</div>
		{/if}
		{if $offer->OdlegloscSzkola != ''}
		<div class="row">
			<div class="key">Odleglość od szkoły [m]</div>
			<div class="val">{$offer->OdlegloscSzkola}</div>
		</div>
		{/if}
		{if $offer->WindaJest != ''}
		<div class="row">
			<div class="key">Winda</div>
			<div class="val">{$offer->WindaJest}</div>
		</div>
		{/if}
		{if $offer->DrzwiAntywlamaniowe != ''}
		<div class="row">
			<div class="key">Drzwi antywłamaniowe</div>
			<div class="val">{$offer->DrzwiAntywlamaniowe}</div>
		</div>
		{/if}
		{if $offer->Klimatyzacja != ''}
		<div class="row">
			<div class="key">Klimatyzacja</div>
			<div class="val">{$offer->Klimatyzacja}</div>
		</div>
		{/if}
		{if $offer->RoletyAntywlamaniowe != ''}
		<div class="row">
			<div class="key">Rolety antywłamaniowe</div>
			<div class="val">{$offer->RoletyAntywlamaniowe}</div>
		</div>
		{/if}
		{if $offer->Telefon != ''}
		<div class="row">
			<div class="key">Telefon</div>
			<div class="val">{$offer->Telefon}</div>
		</div>
		{/if}
		{if $offer->TvKablowa != ''}
		<div class="row">
			<div class="key">Tv kablowa</div>
			<div class="val">{$offer->TvKablowa}</div>
		</div>
		{/if}
		{if $offer->UsytuowanieLista != ''}
		<div class="row">
			<div class="key">Usytuowanie</div>
			<div class="val">{$offer->UsytuowanieLista}</div>
		</div>
		{/if}
        {if $offer->UmeblowanieLista != ''}
		<div class="row">
			<div class="key">Umeblowanie</div>
			<div class="val">{$offer->UmeblowanieLista}</div>
		</div>
		{/if}
		{if $offer->WlasnyParking != ''}
		<div class="row">
			<div class="key">Własny parking</div>
			<div class="val">{$offer->WlasnyParking}</div>
		</div>
		{/if}
	</div>

	<div class="section">
		<div class="tit">Pomieszczenia</div>
		{if $offer->GetRoomsNo() != 0}
		<div class="row">
			<div class="key">Ilość pokoi</div>
			<div class="val">{$offer->GetRoomsNo()}</div>
		</div>
		{/if}
		{if $offer->WysokoscPomieszczen != ''}
		<div class="row">
			<div class="key">Wysokość pomieszczeń</div>
			<div class="val">{$offer->WysokoscPomieszczen}</div>
		</div>
		{/if}
		{if $offer->IloscLazienek != ''}
		<div class="row">
			<div class="key">Ilość łazienek</div>
			<div class="val">{$offer->IloscLazienek}</div>
		</div>
		{/if}
        {if $offer->IloscWc != ''}
		<div class="row">
			<div class="key">Ilość WC</div>
			<div class="val">{$offer->IloscWc}</div>
		</div>
		{/if}
        {if $offer->IloscPrzedpokoi != ''}
		<div class="row">
			<div class="key">Ilość przedpokoi</div>
			<div class="val">{$offer->IloscPrzedpokoi}</div>
		</div>
		{/if}
		{foreach from=$offer->GetRooms() item=room}
			{if $room->GetKind() != ''}
			<div class="row">
				<div class="key b">{$room->GetKind()}</div>
				<div class="val"></div>
			</div>
			{/if}
			{if $room->GetArea() != ''}
			<div class="row">
				<div class="key">Powierzchnia</div>
				<div class="val">{$room->GetArea()}</div>
			</div>
			{/if}
			{if $room->GetLevel() != ''}
			<div class="row">
				<div class="key">GetLevel</div>
				<div class="val">{$room->GetLevel()}</div>
			</div>
			{/if}
			{if $room->GetType() != ''}
			<div class="row">
				<div class="key">Typ pomieszczenia</div>
				<div class="val">{$room->GetType()}</div>
			</div>
			{/if}
			{if $room->GetHeight() != 0}
			<div class="row">
				<div class="key">Wysokość</div>
				<div class="val">{$room->GetHeight()}</div>
			</div>
			{/if}
			{if $room->GetKitchenType() != ''}
			<div class="row">
				<div class="key">Rodzaj kuchni</div>
				<div class="val">{$room->GetKitchenType()}</div>
			</div>
			{/if}
			{if $room->GetNumber() != 0}
			<div class="row">
				<div class="key">Ilość</div>
				<div class="val">{$room->GetNumber()}</div>
			</div>
			{/if}
			{if $room->GetGlaze() != ''}
			<div class="row">
				<div class="key">Glazura</div>
				<div class="val">{$room->GetGlaze()}</div>
			</div>
			{/if}
			{if $room->GetWindowView() != ''}
			<div class="row">
				<div class="key">Wystawa okien</div>
				<div class="val">{$room->GetWindowView()}</div>
			</div>
			{/if}
			{if $room->GetDescription() != ''}
			<div class="row">
				<div class="key">Opis</div>
				<div class="val">{$room->GetDescription()}</div>
			</div>
			{/if}
			{if $room->GetFloorsState() != ''}
			<div class="row">
				<div class="key">Stan podłogi</div>
				<div class="val">{$room->GetFloorsState()}</div>
			</div>
			{/if}
			{if $room->GetRoomType() != ''}
			<div class="row">
				<div class="key">Rodzaj pomieszczenia</div>
				<div class="val">{$room->GetRoomType()}</div>
			</div>
			{/if}
			{if $room->GetWalls()|@count != 0}
			<div class="row">
				<div class="key">Ściany</div>
				<div class="val">{$offer->GetSetAsText($room->GetWalls())}</div>
			</div>
			{/if}
			{if $room->GetFloors()|@count != 0}
			<div class="row">
				<div class="key">Podłogi</div>
				<div class="val">{$offer->GetSetAsText($room->GetFloors())}</div>
			</div>
			{/if}
			{if $room->GetWindowsExhibition()|@count != 0}
			<div class="row">
				<div class="key">Wystawa okien</div>
				<div class="val">{$offer->GetSetAsText($room->GetWindowsExhibition())}</div>
			</div>
			{/if}
			{if $room->GetEquipment()|@count != 0}
			<div class="row">
				<div class="key">Wyposażenie</div>
				<div class="val">{$offer->GetSetAsText($room->GetEquipment())}</div>
			</div>
			{/if}
		{/foreach}
	</div>

	{if $offer->Virtual}
		<div class="section">
			<div class="tit">Wirtualna wizyta</div>
			<iframe style="width:900px;height:600px;border:0;z-index:100;position:relative;" scrolling="no" frameborder="0" data-loaded="1" src="{$offer->Virtual}" allowfullscreen></iframe>
		</div>
	{/if}

	{if $offer->VirtualLink}
		<div class="section">
			<div class="tit">Wirtualna wizyta z linku</div>
			<iframe src="{$offer->VirtualLink}" frameborder="0" allowfullscreen></iframe>
		</div>
	{/if}

	<div class="section">
		<div class="tit">Galeria zdjęć</div>
		{foreach from=$offer->GetPhotos() item=photo}
			<a href="javascript:ShowPhoto({$photo->GetId()}, '_o')"><img src="{$photo->GetImgSrc('120_80', false, false, false)}"/></a>
		{/foreach}
	</div>

	<div class="section swf">
		<div class="tit">Prezentacje FLASH</div>
		{foreach from=$offer->GetSWFs() item=photo}
			<div class="swfFile">
				<a href="javascript:ShowSWF({$photo->GetId()}, '_o')"><img src="{$photo->GetSwfImgSrc('100_75', false, false)}" /></a><br />{$photo->GetFilename()}{$photo->DownloadSWF()}
			</div>
		{/foreach}
	</div>

	<div class="section">
		<div class="tit">Mapa</div>
		{if $offer->GetLatitude() != 0 && $offer->GetLongitude() != 0}
		<div class="row">
			<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAI_u_8fFv2FaLcT97zZyKHBTIE61pJbpvuaVTMuG-iu5z1GsHwRQcfWSPEsjmb8_NS0clDLL5_WLtdA" type="text/javascript" ></script>
			<img src="http://www.google.com/mapfiles/maps_res_logo.gif" style="display:none" onload="LoadMap({$offer->GetLongitude()}, {$offer->GetLatitude()})" onunload="GUnload()">
			<div id="mapa" style="width: 450px; height: 320px;"></div>
		</div>
		{/if}
	</div>

	<div class="section">
		<div class="tit">Kontakt</div>
		{assign var='agent' value=$offer->GetAgentObj()}
		<div class="row">
			<div class="key">Agent</div>
			<div class="val">{$agent->GetName()}</div>
		</div>
        <div class="row">
            <div class="key">Zdjęcie</div>
            <div class="val"><img src="{$agent->GetPhotoImageSrc("200_300")}" /></div>
        </div>
		<div class="row">
			<div class="key">Telefon</div>
			<div class="val">{$agent->GetPhone()}</div>
		</div>
		<div class="row">
			<div class="key">Komórka</div>
			<div class="val">{$agent->GetCell()}</div>
		</div>
		<div class="row">
			<div class="key">E-mail</div>
			<div class="val">{$agent->GetEmail()}</div>
		</div>
        {assign var='odz' value=$agent->GetDepartmentObj()}
        <div class="row">
            <div class="key">Logo oddziału</div>
            <div class="val"><img src="{$odz->GetLogoImageSrc("200_50")}" /></div>
        </div>
        <div class="row">
            <div class="key">Zdjęcie oddziału</div>
            <div class="val"><img src="{$odz->GetPhotoImageSrc("100_100")}" /></div>
        </div>
	</div>
	<a class="clear" href="javascript:history.back()">wróć do poprzedniej strony</a>
</div>

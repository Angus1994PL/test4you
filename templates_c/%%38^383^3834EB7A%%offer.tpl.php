<?php /* Smarty version 2.6.26, created on 2024-11-21 12:51:01
         compiled from offer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'offer.tpl', 447, false),)), $this); ?>
<div class="dvOffer">
	<h3><?php echo $this->_tpl_vars['offer']->GetSymbol(); ?>
 | <?php echo $this->_tpl_vars['offer']->GetShortDescription(); ?>
</h3>
	<div class="section">
		<div class="tit">Informacje ogólne</div>
        <div class="row">
			<div class="key">Status</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetStatus(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">Lokalizacja</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetAllLocation(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">Rodzaj budynku</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetBuildingType(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">Pow. całkowita [m2]</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetArea(); ?>
</div>
		</div>
		<?php if ($this->_tpl_vars['offer']->GetFloor() != ''): ?>
		<div class="row">
			<div class="key">Piętro</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetFloor(); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->GetRoomsNo() != 0): ?>
		<div class="row">
			<div class="key">Ilość pokoi</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetRoomsNo(); ?>
</div>
		</div>
		<?php endif; ?>
		<div class="row">
			<div class="key">Cena</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetPrice(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">Data wprowadzenia</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetCreationDate(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">Data aktualizacji</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetModificationDate(); ?>
</div>
		</div>
		<div class="row">
			<div class="val2"><?php echo $this->_tpl_vars['offer']->UwagiOpis; ?>
</div>
		</div>
	</div>

	<div class="section">
		<div class="tit">Nieruchomość</div>
		<?php if ($this->_tpl_vars['offer']->Standard != ''): ?>
		<div class="row">
			<div class="key">Standard</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Standard; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Kategorie != ''): ?>
		<div class="row">
			<div class="key">Kategorie</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['offer']->Kategorie); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->DodatkoweOplatyWCzynszu != ''): ?>
		<div class="row">
			<div class="key">Opłaty w czynszu</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['offer']->DodatkoweOplatyWCzynszu); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->DodatkoweOplatyWgLicznikow != ''): ?>
		<div class="row">
			<div class="key">Opłaty wg liczników</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['offer']->DodatkoweOplatyWgLicznikow); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->IloscPieter != ''): ?>
		<div class="row">
			<div class="key">Ilość pięter w budynku</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->IloscPieter; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->RodzajMieszkania != ''): ?>
		<div class="row">
			<div class="key">Rodzaj mieszkania</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->RodzajMieszkania; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->PrzeznaczenieDzialkiSet != ''): ?>
		<div class="row">
			<div class="key">Przeznaczenie działki</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['offer']->PrzeznaczenieDzialkiSet); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->ZagospodarowanieDzialki != ''): ?>
		<div class="row">
			<div class="key">Zagospodarowanie działki</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->ZagospodarowanieDzialki; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->UksztaltowanieDzialki != ''): ?>
		<div class="row">
			<div class="key">Ukształtowanie działki</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->UksztaltowanieDzialki; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->KsztaltDzialki != ''): ?>
		<div class="row">
			<div class="key">Kształt działki</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->KsztaltDzialki; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->OgrodzenieDzialki != ''): ?>
		<div class="row">
			<div class="key">Ogrodzenie działki</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->OgrodzenieDzialki; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->GetBuildingTechnology() != ''): ?>
		<div class="row">
			<div class="key">Technologia budowlana</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetBuildingTechnology(); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->StanLokaluLista != ''): ?>
		<div class="row">
			<div class="key">Stan lokalu</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->StanLokaluLista; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->PrzeznaczenieLokalu != ''): ?>
		<div class="row">
			<div class="key">Przeznaczenie lokalu</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['offer']->PrzeznaczenieLokalu); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Okna != ''): ?>
		<div class="row">
			<div class="key">Okna</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Okna; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Instalacje != ''): ?>
		<div class="row">
			<div class="key">Instalacje</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Instalacje; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Balkon != ''): ?>
		<div class="row">
			<div class="key">Balkon</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Balkon; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->IloscBalkonow != ''): ?>
		<div class="row">
			<div class="key">Ilość balkonów</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->IloscBalkonow; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->RodzajDomu != ''): ?>
		<div class="row">
			<div class="key">Rodzaj domu</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->RodzajDomu; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->PokrycieDachu != ''): ?>
		<div class="row">
			<div class="key">Pokrycie dachu</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->PokrycieDachu; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->PozwolenieNaUzytkowanie != ''): ?>
		<div class="row">
			<div class="key">Pozwolenie na użytkowanie</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->PozwolenieNaUzytkowanie; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->PowierzchniaUzytkowa != ''): ?>
		<div class="row">
			<div class="key">Powierzchnia użytkowa</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->PowierzchniaUzytkowa; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Podpiwniczenie != ''): ?>
		<div class="row">
			<div class="key">Podpiwniczenie</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Podpiwniczenie; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->StanBudynku != ''): ?>
		<div class="row">
			<div class="key">Stan budynku</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->StanBudynku; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Garaz != ''): ?>
		<div class="row">
			<div class="key">Garaż</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Garaz; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->GarazMieszkanie != ''): ?>
		<div class="row">
			<div class="key">Garaż</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GarazMieszkanie; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->RokBudowy != ''): ?>
		<div class="row">
			<div class="key">Rok budowy</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->RokBudowy; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->PlacZabaw != ''): ?>
		<div class="row">
			<div class="key">Plac zabaw</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->PlacZabaw; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Gaz != ''): ?>
		<div class="row">
			<div class="key">Gaz</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Gaz; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Woda != ''): ?>
		<div class="row">
			<div class="key">Woda</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Woda; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Kanalizacja != ''): ?>
		<div class="row">
			<div class="key">Kanalizacja</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Kanalizacja; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Prad != ''): ?>
		<div class="row">
			<div class="key">Prąd</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Prad; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Dojazd != ''): ?>
		<div class="row">
			<div class="key">Dojazd</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Dojazd; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Ogrzewanie != ''): ?>
		<div class="row">
			<div class="key">Ogrzewanie</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Ogrzewanie; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->OdlegloscKomunikacja != ''): ?>
		<div class="row">
			<div class="key">Odleglość od komunikacji [m]</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->OdlegloscKomunikacja; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->OdlegloscOdCentrum != ''): ?>
		<div class="row">
			<div class="key">Odleglość od centrum [m]</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->OdlegloscOdCentrum; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->OdlegloscPrzedszkole != ''): ?>
		<div class="row">
			<div class="key">Odleglość od przedszkola [m]</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->OdlegloscPrzedszkole; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->OdlegloscSklep != ''): ?>
		<div class="row">
			<div class="key">Odleglość od sklepu [m]</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->OdlegloscSklep; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->OdlegloscSzkola != ''): ?>
		<div class="row">
			<div class="key">Odleglość od szkoły [m]</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->OdlegloscSzkola; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->WindaJest != ''): ?>
		<div class="row">
			<div class="key">Winda</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->WindaJest; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->DrzwiAntywlamaniowe != ''): ?>
		<div class="row">
			<div class="key">Drzwi antywłamaniowe</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->DrzwiAntywlamaniowe; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Klimatyzacja != ''): ?>
		<div class="row">
			<div class="key">Klimatyzacja</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Klimatyzacja; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->RoletyAntywlamaniowe != ''): ?>
		<div class="row">
			<div class="key">Rolety antywłamaniowe</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->RoletyAntywlamaniowe; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->Telefon != ''): ?>
		<div class="row">
			<div class="key">Telefon</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->Telefon; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->TvKablowa != ''): ?>
		<div class="row">
			<div class="key">Tv kablowa</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->TvKablowa; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->UsytuowanieLista != ''): ?>
		<div class="row">
			<div class="key">Usytuowanie</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->UsytuowanieLista; ?>
</div>
		</div>
		<?php endif; ?>
        <?php if ($this->_tpl_vars['offer']->UmeblowanieLista != ''): ?>
		<div class="row">
			<div class="key">Umeblowanie</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->UmeblowanieLista; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->WlasnyParking != ''): ?>
		<div class="row">
			<div class="key">Własny parking</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->WlasnyParking; ?>
</div>
		</div>
		<?php endif; ?>
	</div>

	<div class="section">
		<div class="tit">Pomieszczenia</div>
		<?php if ($this->_tpl_vars['offer']->GetRoomsNo() != 0): ?>
		<div class="row">
			<div class="key">Ilość pokoi</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->GetRoomsNo(); ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->WysokoscPomieszczen != ''): ?>
		<div class="row">
			<div class="key">Wysokość pomieszczeń</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->WysokoscPomieszczen; ?>
</div>
		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['offer']->IloscLazienek != ''): ?>
		<div class="row">
			<div class="key">Ilość łazienek</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->IloscLazienek; ?>
</div>
		</div>
		<?php endif; ?>
        <?php if ($this->_tpl_vars['offer']->IloscWc != ''): ?>
		<div class="row">
			<div class="key">Ilość WC</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->IloscWc; ?>
</div>
		</div>
		<?php endif; ?>
        <?php if ($this->_tpl_vars['offer']->IloscPrzedpokoi != ''): ?>
		<div class="row">
			<div class="key">Ilość przedpokoi</div>
			<div class="val"><?php echo $this->_tpl_vars['offer']->IloscPrzedpokoi; ?>
</div>
		</div>
		<?php endif; ?>
		<?php $_from = $this->_tpl_vars['offer']->GetRooms(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['room']):
?>
			<?php if ($this->_tpl_vars['room']->GetKind() != ''): ?>
			<div class="row">
				<div class="key b"><?php echo $this->_tpl_vars['room']->GetKind(); ?>
</div>
				<div class="val"></div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetArea() != ''): ?>
			<div class="row">
				<div class="key">Powierzchnia</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetArea(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetLevel() != ''): ?>
			<div class="row">
				<div class="key">GetLevel</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetLevel(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetType() != ''): ?>
			<div class="row">
				<div class="key">Typ pomieszczenia</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetType(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetHeight() != 0): ?>
			<div class="row">
				<div class="key">Wysokość</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetHeight(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetKitchenType() != ''): ?>
			<div class="row">
				<div class="key">Rodzaj kuchni</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetKitchenType(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetNumber() != 0): ?>
			<div class="row">
				<div class="key">Ilość</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetNumber(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetGlaze() != ''): ?>
			<div class="row">
				<div class="key">Glazura</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetGlaze(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetWindowView() != ''): ?>
			<div class="row">
				<div class="key">Wystawa okien</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetWindowView(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetDescription() != ''): ?>
			<div class="row">
				<div class="key">Opis</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetDescription(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetFloorsState() != ''): ?>
			<div class="row">
				<div class="key">Stan podłogi</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetFloorsState(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['room']->GetRoomType() != ''): ?>
			<div class="row">
				<div class="key">Rodzaj pomieszczenia</div>
				<div class="val"><?php echo $this->_tpl_vars['room']->GetRoomType(); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if (count($this->_tpl_vars['room']->GetWalls()) != 0): ?>
			<div class="row">
				<div class="key">Ściany</div>
				<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['room']->GetWalls()); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if (count($this->_tpl_vars['room']->GetFloors()) != 0): ?>
			<div class="row">
				<div class="key">Podłogi</div>
				<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['room']->GetFloors()); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if (count($this->_tpl_vars['room']->GetWindowsExhibition()) != 0): ?>
			<div class="row">
				<div class="key">Wystawa okien</div>
				<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['room']->GetWindowsExhibition()); ?>
</div>
			</div>
			<?php endif; ?>
			<?php if (count($this->_tpl_vars['room']->GetEquipment()) != 0): ?>
			<div class="row">
				<div class="key">Wyposażenie</div>
				<div class="val"><?php echo $this->_tpl_vars['offer']->GetSetAsText($this->_tpl_vars['room']->GetEquipment()); ?>
</div>
			</div>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	</div>

	<?php if ($this->_tpl_vars['offer']->Virtual): ?>
		<div class="section">
			<div class="tit">Wirtualna wizyta</div>
			<iframe style="width:900px;height:600px;border:0;z-index:100;position:relative;" scrolling="no" frameborder="0" data-loaded="1" src="<?php echo $this->_tpl_vars['offer']->Virtual; ?>
" allowfullscreen></iframe>
		</div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['offer']->VirtualLink): ?>
		<div class="section">
			<div class="tit">Wirtualna wizyta z linku</div>
			<iframe src="<?php echo $this->_tpl_vars['offer']->VirtualLink; ?>
" frameborder="0" allowfullscreen></iframe>
		</div>
	<?php endif; ?>

	<div class="section">
		<div class="tit">Galeria zdjęć</div>
		<?php $_from = $this->_tpl_vars['offer']->GetPhotos(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['photo']):
?>
			<a href="javascript:ShowPhoto(<?php echo $this->_tpl_vars['photo']->GetId(); ?>
, '_o')"><img src="<?php echo $this->_tpl_vars['photo']->GetImgSrc('120_80',false,false,false); ?>
"/></a>
		<?php endforeach; endif; unset($_from); ?>
	</div>

	<div class="section swf">
		<div class="tit">Prezentacje FLASH</div>
		<?php $_from = $this->_tpl_vars['offer']->GetSWFs(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['photo']):
?>
			<div class="swfFile">
				<a href="javascript:ShowSWF(<?php echo $this->_tpl_vars['photo']->GetId(); ?>
, '_o')"><img src="<?php echo $this->_tpl_vars['photo']->GetSwfImgSrc('100_75',false,false); ?>
" /></a><br /><?php echo $this->_tpl_vars['photo']->GetFilename(); ?>
<?php echo $this->_tpl_vars['photo']->DownloadSWF(); ?>

			</div>
		<?php endforeach; endif; unset($_from); ?>
	</div>

	<div class="section">
		<div class="tit">Mapa</div>
		<?php if ($this->_tpl_vars['offer']->GetLatitude() != 0 && $this->_tpl_vars['offer']->GetLongitude() != 0): ?>
		<div class="row">
			<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAI_u_8fFv2FaLcT97zZyKHBTIE61pJbpvuaVTMuG-iu5z1GsHwRQcfWSPEsjmb8_NS0clDLL5_WLtdA" type="text/javascript" ></script>
			<img src="http://www.google.com/mapfiles/maps_res_logo.gif" style="display:none" onload="LoadMap(<?php echo $this->_tpl_vars['offer']->GetLongitude(); ?>
, <?php echo $this->_tpl_vars['offer']->GetLatitude(); ?>
)" onunload="GUnload()">
			<div id="mapa" style="width: 450px; height: 320px;"></div>
		</div>
		<?php endif; ?>
	</div>

	<div class="section">
		<div class="tit">Kontakt</div>
		<?php $this->assign('agent', $this->_tpl_vars['offer']->GetAgentObj()); ?>
		<div class="row">
			<div class="key">Agent</div>
			<div class="val"><?php echo $this->_tpl_vars['agent']->GetName(); ?>
</div>
		</div>
        <div class="row">
            <div class="key">Zdjęcie</div>
            <div class="val"><img src="<?php echo $this->_tpl_vars['agent']->GetPhotoImageSrc('200_300'); ?>
" /></div>
        </div>
		<div class="row">
			<div class="key">Telefon</div>
			<div class="val"><?php echo $this->_tpl_vars['agent']->GetPhone(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">Komórka</div>
			<div class="val"><?php echo $this->_tpl_vars['agent']->GetCell(); ?>
</div>
		</div>
		<div class="row">
			<div class="key">E-mail</div>
			<div class="val"><?php echo $this->_tpl_vars['agent']->GetEmail(); ?>
</div>
		</div>
        <?php $this->assign('odz', $this->_tpl_vars['agent']->GetDepartmentObj()); ?>
        <div class="row">
            <div class="key">Logo oddziału</div>
            <div class="val"><img src="<?php echo $this->_tpl_vars['odz']->GetLogoImageSrc('200_50'); ?>
" /></div>
        </div>
        <div class="row">
            <div class="key">Zdjęcie oddziału</div>
            <div class="val"><img src="<?php echo $this->_tpl_vars['odz']->GetPhotoImageSrc('100_100'); ?>
" /></div>
        </div>
	</div>
	<a class="clear" href="javascript:history.back()">wróć do poprzedniej strony</a>
</div>
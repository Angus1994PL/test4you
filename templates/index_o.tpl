<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Example usage of VIRGO API PHP</title>
	<script type="text/javascript" src="js/scripts.js" ></script>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">{$ajax}</script>
	{if $ShowSWF}<base id="BaseLink" href="http://{$photo->GetBaseLink()}"></base>{/if}
</head>
<body>
<form action="index_o.php" method="post" id="frmMain">
	<input type="hidden" name="hidAction" id="hidAction" />	
	
	<div class="dvMain">
		{if !$ShowPhoto}
            <div>
                <div style="float: left; width: 150px; height: 20px;">Przejdź do <a href="index_i.php">inwestycji</a></div>
                <div style="float: left;">Wybierz język:
                    {foreach from=$Languages item=lng}
                    <input type="radio" id="lng_{$lng->GetId()}" value="{$lng->GetId()}" name="lng" {if $Lng==$lng->GetId()}checked="true"{/if} onchange="window.location = 'index_o.php?lng={$lng->GetId()}';" /><label for="lng_{$lng->GetId()}">{$lng->GetName()}</label>
                    {/foreach}
                </div>
                <div style="float: left; margin-left: 20px;">
                    <a href="{$ApiObj->GetContactFormAddress()}" target="_blank">Formularz kontaktowy</a>&nbsp;|&nbsp;
                    <a href="{$ApiObj->GetNewOfferFormAddress()}" target="_blank">Zgłoś ofertę</a>&nbsp;|&nbsp;
                    <a href="{$ApiObj->GetNewSearchFormAddress()}" target="_blank">Zgłoś poszukiwanie</a>
                    {if $ShowOfferDetails}&nbsp;|&nbsp;<a href="{$ApiObj->GetContactPerOfferFormAddress($offer->GetId())}" target="_blank">Kontakt do oferty</a>{/if}
                </div>
                <div style="float: left; margin-left: 50px;">
                    Newsletter, podaj email: <input type="text" name="nlEmail" maxlength="100" />
                    <input type="button" value="Dodaj" onclick="DoPostBack('newsLetterAdd', '', '')"/>
                    <input type="button" value="Usuń" onclick="DoPostBack('newsLetterDel', '', '')"/>
                </div>
            </div><hr style="clear: both;" />
            {if $infoMsg}<div class="dvInfo">{$infoMsg}</div>{/if}
		{/if}
		{if $ShowSearchForm}
			{include file="search.tpl" lng=$lng}
		{/if}
		{if $ShowSpecialOffers}
			{include file="special.tpl"}
		{/if}
		{if $ShowOffersList}
			{include file="offers.tpl"}
		{/if}
		{if $ShowOfferDetails}
			{include file="offer.tpl"}
		{/if}
		{if $ShowPhoto}
			<img src="{$photo->GetImgSrc('710_520', true, true)}" onclick="window.close()" style="cursor: pointer;" id="fotoID" />
			<script type="text/javascript">setTimeout('setTimeout("Chsize()",100)', 100);</script>
		{/if}
		{if $ShowSWF}
			<object type="application/x-shockwave-flash" data="{$photo->GetSWFSrc()}" width="544" height="470">
				<param name="movie" value="{$photo->GetSWFSrc()}" />
				<param name="wmode" value="transparent" />
				<param name="allowFullScreen" value="true" />
			</object>  
		{/if}
	</div>

	{$synchronizeDB}
</form>
</body>
</html>
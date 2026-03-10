<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Example usage of VIRGO API PHP</title>
	<script type="text/javascript" src="js/scripts.js" ></script>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">{$ajax}</script>
</head>
<body>
<form action="index_i.php" method="post" id="frmMain">
	<input type="hidden" name="hidAction" id="hidAction" />	
	
	<div class="dvMain">
		
		{if !$ShowPhoto}
            <div>
                <div style="float: left; width: 200px; height: 20px;">Przejdź do <a href="index_o.php">ofert</a></div>
                <div style="float: left;">Wybierz język:
                    {foreach from=$Languages item=lng}
                    <input type="radio" id="lng_{$lng->GetId()}" value="{$lng->GetId()}" name="lng" {if $Lng==$lng->GetId()}checked="true"{/if} onchange="window.location = 'index_i.php?lng={$lng->GetId()}';" /><label for="lng_{$lng->GetId()}">{$lng->GetName()}</label>
                    {/foreach}
                </div>
            </div><hr style="clear: both;" />
		{/if}
		{if $ShowSearchForm}
			{include file="search_i.tpl"}
		{/if}
		{if $ShowInvestmentsList}
			{include file="investments.tpl"}
		{/if}
		{if $ShowInvestmentDetails}
			{include file="investment.tpl"}
		{/if}
		{if $ShowPhoto}
			<img src="{$photo->GetImgSrc('640_480', true, true)}" onclick="window.close()" style="cursor: pointer;" id="fotoID" />
			<script type="text/javascript">setTimeout('setTimeout("Chsize()",100)', 100);</script>
		{/if}		
	</div>

	{$synchronizeDB}
</form>
</body>
</html>
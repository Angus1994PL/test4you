<div class="dvPages">	
	<div class="p"><img src="images/nav_first.gif" onclick="DoPostBack('{$argument}', '{$hidId}', '{$args->getFirst()}')" /></div>
	<div class="p"><img src="images/nav_prev.gif" onclick="DoPostBack('{$argument}', '{$hidId}', '{$args->getPrev()}')" /></div>
	<div class="ods">&nbsp;</div>
	{assign var='pages' value=$args->GetPagesNumbers()}
	{assign var='lastPage' value=$args->ShowLastPage()}
	{foreach from=$pages item=pg}			
		<div class="p" onclick="DoPostBack('{$argument}', '{$hidId}', '{$pg}')"><span {if $args->ActualPage == $pg}style="color: red;"{/if}>[{$pg+1}]</span></div>
	{/foreach}
	{if $lastPage > 0}<div class="ods">&nbsp;</div><div class="p" onclick="DoPostBack('{$argument}', '{$hidId}', '{$lastPage}')">[{$lastPage+1}]</div>{/if}
	<div class="ods">&nbsp;</div>
	<div class="p"><img src="images/nav_next.gif" onclick="DoPostBack('{$argument}', '{$hidId}', '{$args->getNext()}')" /></div>
	<div class="p"><img src="images/nav_last.gif" onclick="DoPostBack('{$argument}', '{$hidId}', '{$args->getLast()}')" /></div>
</div>
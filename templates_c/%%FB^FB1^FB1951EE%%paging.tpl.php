<?php /* Smarty version 2.6.26, created on 2018-12-18 10:30:41
         compiled from paging.tpl */ ?>
<div class="dvPages">	
	<div class="p"><img src="images/nav_first.gif" onclick="DoPostBack('<?php echo $this->_tpl_vars['argument']; ?>
', '<?php echo $this->_tpl_vars['hidId']; ?>
', '<?php echo $this->_tpl_vars['args']->getFirst(); ?>
')" /></div>
	<div class="p"><img src="images/nav_prev.gif" onclick="DoPostBack('<?php echo $this->_tpl_vars['argument']; ?>
', '<?php echo $this->_tpl_vars['hidId']; ?>
', '<?php echo $this->_tpl_vars['args']->getPrev(); ?>
')" /></div>
	<div class="ods">&nbsp;</div>
	<?php $this->assign('pages', $this->_tpl_vars['args']->GetPagesNumbers()); ?>
	<?php $this->assign('lastPage', $this->_tpl_vars['args']->ShowLastPage()); ?>
	<?php $_from = $this->_tpl_vars['pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pg']):
?>			
		<div class="p" onclick="DoPostBack('<?php echo $this->_tpl_vars['argument']; ?>
', '<?php echo $this->_tpl_vars['hidId']; ?>
', '<?php echo $this->_tpl_vars['pg']; ?>
')"><span <?php if ($this->_tpl_vars['args']->ActualPage == $this->_tpl_vars['pg']): ?>style="color: red;"<?php endif; ?>>[<?php echo $this->_tpl_vars['pg']+1; ?>
]</span></div>
	<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['lastPage'] > 0): ?><div class="ods">&nbsp;</div><div class="p" onclick="DoPostBack('<?php echo $this->_tpl_vars['argument']; ?>
', '<?php echo $this->_tpl_vars['hidId']; ?>
', '<?php echo $this->_tpl_vars['lastPage']; ?>
')">[<?php echo $this->_tpl_vars['lastPage']+1; ?>
]</div><?php endif; ?>
	<div class="ods">&nbsp;</div>
	<div class="p"><img src="images/nav_next.gif" onclick="DoPostBack('<?php echo $this->_tpl_vars['argument']; ?>
', '<?php echo $this->_tpl_vars['hidId']; ?>
', '<?php echo $this->_tpl_vars['args']->getNext(); ?>
')" /></div>
	<div class="p"><img src="images/nav_last.gif" onclick="DoPostBack('<?php echo $this->_tpl_vars['argument']; ?>
', '<?php echo $this->_tpl_vars['hidId']; ?>
', '<?php echo $this->_tpl_vars['args']->getLast(); ?>
')" /></div>
</div>
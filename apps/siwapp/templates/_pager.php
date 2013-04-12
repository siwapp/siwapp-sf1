<div id="bd-paginator">
<?php if ($pager->haveToPaginate()): ?>
  <?php $url = url_for($route) . '?page='; ?>
  <form action="<?php echo $url ?>" method="get">
	  <ul class="inline small">
	    <li><?php echo link_to(__('First'), $url.$pager->getFirstPage()) ?> |</li>
	    <li><?php echo link_to(__('Prev'), $url.$pager->getPreviousPage()) ?> |</li>
	    <li class="small">
	      
	      <?php echo __('PAGE').' '.$pager->getPage().' / '.$pager->getLastPage() ?>
	    </li>
	    <li>| <?php echo link_to(__('Next'), $url.$pager->getNextPage()) ?> |</li>
	    <li><?php echo link_to(__('Last'), $url.$pager->getLastPage()) ?> |</li>
	    <li>
	      <?php echo __('go to ') ?><input type="text" name="page" id="page" size="2">&nbsp;
	      <?php echo gButton(__('Go'), 'type=submit class=btn primary', 'button=true') ?>
	    </li>
	  </ul>
  </form>
<?php endif; ?>

  <div class="small">
    <?php echo __('Showing [1]-[2] of [3] results', array(
      '[1]' => $pager->getFirstIndice(), 
      '[2]' => $pager->getLastIndice(), 
      '[3]' => '<strong>' . $pager->getNbResults() . '</strong>'
    )); ?>
  </div>
</div>


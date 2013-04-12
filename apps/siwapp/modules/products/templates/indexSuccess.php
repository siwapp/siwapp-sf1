<?php
use_helper('JavascriptBase', 'Number', 'Siwapp', 'Date');

$products = $pager->getResults();
$csrf     = new sfForm();
?>

<div class="content">
  
  <?php if (count($products)): ?>
    
    <?php echo form_tag('products/batch', 'id=batch_form class=batch') ?>
      <?php echo $csrf['_csrf_token']->render(); ?>
      <input type="hidden" name="batch_action" id="batch_action">


      <table id="listing" class="listing">
        <thead>

          <tr class="empty noborder listing-options">
            <td colspan="2">
              <?php echo gButton_to_function(__("Delete"), "do_batch('delete')", array('class'=>'batch delete action-clear', 'confirm'=>__('Are you sure?'))) ?>
            </td>   
            <td colspan="4" class="strong noborder right"> 
              <?php echo __(
                            '%q% products sold for a total of %t%', 
                            array(
                                  '%q%'=>$quantity,
                                  '%t%'=>format_currency($sold,$currency)
                                  )
                            )?>
            </td>
          </tr>

          <tr class="empty noborder">
            <td colspan="1000"></td>
          </tr>

          <tr>
            <th class="xs"><input id="select_all" rel="all" type="checkbox" name="select_all"></th>
            <?php
              // sort parameter => array (Name, default order)
              renderHeaders(array(
                'reference' => array('Reference', 'asc'),
                'description'    => array('Description', 'desc'),
                'price'    => array('Price', 'desc'),
                'quantity' => array('Units', 'desc'),
                'sold'     => array('Sold', 'desc')
                ), $sf_data->getRaw('sort'), '@products');
            ?>
            
          </tr>
        </thead>

        <tbody>
          <?php foreach ($products as $i => $product): ?>
            <?php
              $id       = $product->getId();
              $parity   = ($i % 2) ? 'odd' : 'even';
            ?>
            <tr id="product-<?php echo $id ?>" class="<?php echo "$parity link product-$id " ?>">
              <td class="check"><input rel="item" type="checkbox" value="<?php echo $id ?>" name="ids[]"></td>
              <td><?php echo $product->reference ?></td>
                <td><?php echo $product->description ?></td>
              <td><?php echo $product->price ?></td>            
              <td><?php echo $product->quantity ?></td>
              <td><?php echo format_currency($product->sold, $currency)?></td>
            </tr>
          <?php endforeach ?>
        </tbody>

        <tfoot>
          <tr class="noborder">
            <td colspan="10" class="listing-options">
              <?php echo gButton_to_function(__("Delete"), "do_batch('delete')", array('class'=>'batch delete action-clear', 'confirm'=>__('Are you sure?'))) ?>
            </td>
          </tr>
        </tfoot>

      </table>
    </form>

    <?php include_partial('global/pager', array('pager' => $pager, 'route' => '@products')) ?>
    
  <?php else: ?>
    <p><?php echo __('No results') ?></p>
  <?php endif ?>
  
</div>

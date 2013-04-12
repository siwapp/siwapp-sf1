<?php
/**
 * See http://dev.markhaus.com/projects/siwapp/wiki/howto_send_notifications for more info.
 * Carlos <carlos@markhaus.com>
 */

use_helper('JavascriptBase', 'Text');

$triggers = null;
foreach (array('error', 'warning', 'info') as $type)
{
  if ($sf_user->hasFlash($type))
  {
    foreach($sf_user->getFlash($type) as $message)
    {
      $triggers .= ".trigger('NotificationEvent', { type: '$type', message: '".__($message)."' })";
    }
  }
}
$triggers = "$(document)".$triggers.";";
?>

<div id="hd-notifications" class="content"></div>

<?php echo javascript_tag("
var notifications = 0;

$(document).bind('NotificationEvent', function(e, data) {
  notifications++;
  var next = (notifications > 1 ? '<small>' + (notifications - 1) + ' ".__('more')."...</small>' : '');
  var html = $('<div class=\"notification ' + data.type + '\" style=\"display:none;\">' + next + data.message + '</div>');
  
  $('#hd-notifications').append(html);
  
  $('html, body').animate({ scrollTop: 0 }, 'fast', function() {
    html.oneTime(500, function(){
      $(this).slideDown(500);
    });
  });
}).bind('NotificationRemoveEvent', function() {
  notifications--;
});

$('#hd-notifications .notification').live('click', function() {
  $(this).slideUp(500);
  $(document).trigger('NotificationRemoveEvent');
});

$triggers;
") ?>
<?php

/**
 * Alternative disposition of rendered form rows
 * @author Carlos Escribano <carlos@markhaus.com>
 * @version    SVN: $Id: sfWidgetFormSchemaFormatterList.class.php 5995 2007-11-13 15:50:03Z fabien $
 */
class sfWidgetFormSchemaFormatterListB extends sfWidgetFormSchemaFormatterList
{
  protected
    $rowFormat       = "<li>\n  %label%\n  %field%%help%\n  %error%\n  %hidden_fields%</li>\n";
}

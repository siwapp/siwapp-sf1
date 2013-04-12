<?php
/**
 * remove labels
 *
 **/
class sfWidgetFormSchemaFormatterNoLabels extends sfWidgetFormSchemaFormatterList
{
  protected
    $rowFormat       = "<li>\n  %error%\n %field%%help%\n</li>\n%hidden_fields%";
}

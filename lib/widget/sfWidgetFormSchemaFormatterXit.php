<?php
/**
 * adds the xit link to the form and remove labels from fields
 *
 **/
class sfWidgetFormSchemaFormatterXit extends sfWidgetFormSchemaFormatterList
{
  protected
    $rowFormat       = "<li>\n  %error%\n %field%%help%\n</li>\n%hidden_fields%",
    $decoratorFormat = "<ul><a href=\"#\" class=\"remove xit\" ></a>\n  %content%</ul>";
}

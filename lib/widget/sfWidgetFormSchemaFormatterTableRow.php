<?php
class sfWidgetFormSchemaFormatterTableRow extends sfWidgetFormSchemaFormatterTable
{
  protected
    $rowFormat       = "<td class=\"%error_class%\">%hidden_fields%\n  %field%\n%error%\n</td>",
    $decoratorFormat = "<tr class=\"nameValueItem\"><td><a href=\"#\" class=\"remove xit\"></a></td>%content%</tr>",
    $errorRowFormat  = '';

  public function formatRow($label,$field,$errors = array(),$help = '',$hiddenFields = null)
  {
    $row = parent::formatRow($label,$field,$errors = array(),$help = '',$hiddenFields = null);
    return strtr($row,array('%error_class%'=>(count($errors)>0)?'error wrong':''));
  }
}
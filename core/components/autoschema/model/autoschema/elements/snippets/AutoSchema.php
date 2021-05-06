
  
<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
$autoschema = $modx->getService('autoschema', 'AutoSchema', $modx->getOption('autoschema.core_path', null, $modx->getOption('core_path') . 'components/autoschema/') . 'model/autoschema/');
if (!($autoschema instanceof \AutoSchema)) return '';

return (new \AutoSchema\Snippet\AutoSchema($autoschema, $scriptProperties))->process();
<?php

class AutoSchema
{
    /**
     * @var modX|null $modx
     */
    public $modx = null;
    /**
     * @var array
     */
    public $options = [];
    public $namespace = 'autoschema';

    public function __construct(modX &$modx, array $options = [])
    {
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('autoschema.core_path', $options, $this->modx->getOption('core_path') . 'components/autoschema/');
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'basePath' => $this->modx->getOption('base_path'),
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'snippetPath' => $corePath . 'elements/snippets/',
            'pluginPath' => $corePath . 'elements/plugin/',
        ], $options);
        $this->modx->addPackage($this->namespace, $this->options['modelPath']);
        $this->autoload();
    }

    protected function autoload()
    {
        require_once $this->getOption('modelPath') . 'vendor/autoload.php';
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }
}

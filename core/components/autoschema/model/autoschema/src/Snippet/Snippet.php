<?php

namespace AutoSchema\Snippet;

abstract class Snippet
{
    /** @var \modX */
    protected $modx;

    /** @var \AutoSchema */
    protected $autoschema;

    /** @var array */
    protected $properties = [];

    /** @var bool */
    protected $debug = false;

    public function __construct(\AutoSchema &$autoschema, array $properties = [])
    {
        $this->autoschema =& $autoschema;
        $this->modx =& $this->autoschema->modx;
        $this->properties = $properties;
        $this->debug = (bool)$this->getOption('debug', 0);
    }

    abstract public function process();

    protected function getOption($key, $default = null, $skipEmpty = true)
    {
        return $this->modx->getOption($key, $this->properties, $default, $skipEmpty);
    }

    protected function getChunk($tpl, $phs = [])
    {
        if (strpos($tpl, '@INLINE ') !== false) {
            $content = str_replace('@INLINE ', '', $tpl);

            /** @var \modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk', array('name' => 'inline-' . uniqid('', true)));
            $chunk->setCacheable(false);

            return $chunk->process($phs, $content);
        }

        return $this->modx->getChunk($tpl, $phs);
    }
}

<?php
namespace Lib;

use Lib\AnodocLib\ClassDoc;
use Lib\AnodocLib\Parser;
use Lib\AnodocLib\RawDocRetriever;

/**
 * Class Anodoc 解析PHP中的描述信息
 * @package Lib
 */
class Anodoc
{
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public static function getNew()
    {
        return new self(new Parser);
    }

    public function getDoc($class)
    {
        $retriever = new RawDocRetriever($class);
        return new ClassDoc(
            $class,
            $this->parser->parse($retriever->rawClassDoc()),
            $this->getParsedDocs($retriever->rawMethodDocs()),
            $this->getParsedDocs($retriever->rawAttrDocs())
        );
    }

    private function getParsedDocs($rawDocs)
    {
        $docs = array();
        foreach ($rawDocs as $name => $doc) {
            $docs[$name] = $this->parser->parse($doc);
        }
        return $docs;
    }

    public function registerTag($tag_name, $tag_class)
    {
        $this->parser->registerTag($tag_name, $tag_class);
    }

    /**
     * Used for easier classloading
     *
     * e.g.:
     * require_once 'Anodoc.php';
     * $classLoader->register('Anodoc', Anodoc::getSourceLocation());
     */
    public static function getSourceLocation()
    {
        return __DIR__;
    }
}

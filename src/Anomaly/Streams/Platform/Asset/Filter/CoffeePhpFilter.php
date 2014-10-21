<?php namespace Anomaly\Streams\Platform\Asset\Filter;

use CoffeeScript\Compiler;
use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

class CoffeePhpFilter implements FilterInterface
{

    public function filterLoad(AssetInterface $asset)
    {
        //
    }

    public function filterDump(AssetInterface $asset)
    {

        $content = $asset->getContent(app('view')->parse($asset->getContent()));

        $content = trim(Compiler::compile($content, array('filename' => $asset->getSourcePath())));

        $asset->setContent($content);
    }

}
<?php namespace Streams\Platform\Addon\Block;

use Streams\Platform\Addon\Addon;

class BlockAddon extends Addon
{
    public function newPresenter()
    {
        return new BlockPresenter($this);
    }

    public function newServiceProvider()
    {
        return new BlockServiceProvider($this->app);
    }
}
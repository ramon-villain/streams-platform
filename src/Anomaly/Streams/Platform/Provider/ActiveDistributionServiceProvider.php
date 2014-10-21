<?php namespace Anomaly\Streams\Platform\Provider;

use Illuminate\Support\ServiceProvider;

class ActiveDistributionServiceProvider extends ServiceProvider
{
    /**
     * Defer loading this service provider.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Setup the environment with the active distribution.
     */
    public function register()
    {
        $distribution = app('streams.distribution');

        // Setup namespace hints for a short namespace.
        app('view')->addNamespace('distribution', $distribution->getPath('resources/views'));
        app('streams.asset')->addNamespace('distribution', $distribution->getPath('resources'));
        app('streams.image')->addNamespace('distribution', $distribution->getPath('resources'));
        app('translator')->addNamespace('distribution', $distribution->getPath('resources/lang'));
    }
}
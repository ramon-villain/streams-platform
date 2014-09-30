<?php namespace Streams\Platform\Provider;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        // For now just include the helpers PHP file.
        include __DIR__ . '/../../../../resources/helpers.php';
    }
}

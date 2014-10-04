<?php namespace Streams\Platform\Addon;

use Illuminate\Support\ServiceProvider;
use Streams\Platform\Traits\CallableTrait;

abstract class AddonServiceProvider extends ServiceProvider
{
    use CallableTrait;

    protected $binding = 'singleton';

    public function register()
    {
        $loaded = [];

        foreach ($this->getAddonPaths() as $path) {

            $slug = basename($path);

            // We have to register the PSR src folder
            // before we can continue.
            $this->registerSrcFolder($slug, $path);

            // Register the addon class to the container.
            $this->registerAddonClass($slug);

            $loaded[] = $this->getAbstract($slug);
        }

        // Register loaded addon abstracts
        // so we can access them later.
        $this->registerLoadedAddons($loaded);

        $this->fire('after_register');
    }

    protected function registerSrcFolder($slug, $path)
    {
        app('streams.loader')->addPsr4(
            $this->getNamespace($slug) . '\\',
            $path . '/src'
        );
    }

    protected function registerAddonClass($slug)
    {
        $class = $this->getClass($slug);

        $this->app->{$this->binding}(
            $this->getAbstract($slug),
            function () use ($class) {
                return new $class;
            }
        );
    }

    protected function registerLoadedAddons($loaded)
    {
        $this->app->singleton(
            "streams.{$this->getType()}.loaded",
            function () use ($loaded) {
                return array_unique($loaded);
            }
        );
    }

    protected function getAddonPaths()
    {
        $corePaths        = $this->getCoreAddonPaths();
        $sharedPaths      = $this->getSharedAddonPaths();
        $applicationPaths = $this->getApplicationAddonPaths();

        return array_merge($corePaths, $sharedPaths, $applicationPaths);
    }

    protected function getCoreAddonPaths()
    {
        $path = base_path('core/addons/' . $this->getFolder());

        if (is_dir($path)) {
            return app('files')->directories($path);
        }

        return [];
    }

    protected function getSharedAddonPaths()
    {
        $path = base_path('addons/shared/' . $this->getFolder());

        if (is_dir($path)) {
            return app('files')->directories($path);
        }

        return [];
    }

    protected function getApplicationAddonPaths()
    {
        $reference = app('streams.application')->getReference();

        $path = base_path('addons/' . $reference . '/' . $this->getFolder());

        if (is_dir($path)) {
            return app('files')->directories($path);
        }

        return [];
    }

    protected function getType()
    {
        $class = (new \ReflectionClass($this))->getShortName();

        return snake_case(str_replace('ServiceProvider', '', $class));
    }

    protected function getFolder()
    {
        return str_plural($this->getType());
    }

    protected function getNamespace($slug)
    {
        return 'Streams\Addon\\' . studly_case(basename($this->getType())) . '\\' . studly_case($slug);
    }

    protected function getClass($slug)
    {
        return $this->getNamespace($slug) . '\\' . studly_case($slug) . studly_case($this->getType());
    }

    protected function getAbstract($slug)
    {
        return "streams.{$this->getType()}.{$slug}";
    }
}
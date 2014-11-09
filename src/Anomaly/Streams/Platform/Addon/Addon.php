<?php namespace Anomaly\Streams\Platform\Addon;

use Anomaly\Streams\Platform\Contract\ArrayableInterface;
use Anomaly\Streams\Platform\Traits\CallableTrait;
use Anomaly\Streams\Platform\Traits\TransformableTrait;

/**
 * Class Addon
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Addon
 */
class Addon implements ArrayableInterface
{

    use CallableTrait;
    use TransformableTrait;

    /**
     * The addon path.
     * This is set automatically.
     *
     * @var null
     */
    protected $path = null;

    /**
     * The addon type.
     * This is set automatically.
     *
     * @var
     */
    protected $type;

    /**
     * The addon slug.
     * This is set automatically.
     *
     * @var
     */
    protected $slug;

    /**
     * Get the lang string for a given key.
     *
     * @param $key
     * @return string
     */
    public function lang($key)
    {
        return "{$this->getType()}.{$this->getSlug()}::{$key}";
    }

    /**
     * Get the addon path. Optionally include an
     * additional path suffix.
     *
     * @param null $path
     * @return string
     */
    public function getPath($path = null)
    {
        if (!$this->path) {

            $this->path = dirname(dirname((new \ReflectionClass($this))->getFileName()));
        }

        return $this->path . ($path ? '/' . ltrim($path, '/') : null);
    }

    /**
     * Get the core addon flag.
     *
     * @return bool
     */
    public function isCore()
    {
        return str_contains($this->getPath(), 'core/addons');
    }

    /**
     * Get the addon slug.
     *
     * @return string
     */
    public function getSlug()
    {
        if (!$this->slug) {

            $class = get_class($this);
            $parts = explode("\\", $class);

            $this->slug = snake_case($parts[count($parts) - 2]);
        }

        return $this->slug;
    }

    /**
     * Get the addon type.
     *
     * @return string
     */
    public function getType()
    {
        if (!$this->type) {

            $class = get_class($this);
            $parts = explode("\\", $class);

            $this->type = snake_case($parts[count($parts) - 3]);
        }

        return $this->type;
    }

    /**
     * Get the addon abstract string.
     *
     * @return string
     */
    public function getAbstract()
    {
        return "streams.{$this->getType()}.{$this->getSlug()}";
    }

    /**
     * Get the addon name string.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getType() . '.' . $this->getSlug() . '::addon.name';
    }

    /**
     * Get the addon description string.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getType() . '.' . $this->getSlug() . '::addon.description';
    }

    /**
     * Return the addons service provider counterpart.
     *
     * @return null|string
     */
    public function toServiceProvider()
    {
        return $this->transform(__FUNCTION__);
    }

    /**
     * Return the permissions class for the addon.
     *
     * @return null
     */
    public function newPermissions()
    {
        if (!$permissions = $this->transform(__FUNCTION__)) {

            return null;
        }

        return app()->make($permissions, [$this]);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'slug'        => $this->getSlug(),
            'path'        => $this->getPath(),
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }
}

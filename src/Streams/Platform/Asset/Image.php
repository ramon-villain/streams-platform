<?php namespace Streams\Platform\Asset;

use Intervention\Image\ImageManager;

class Image extends ImageManager
{
    /**
     * Asset paths by binding.
     *
     * @var array
     */
    protected $namespaces = [];

    /**
     * The image we're working with.
     *
     * @var null
     */
    protected $image = null;

    /**
     * Filters to apply to the image.
     *
     * @var array
     */
    protected $applied = [];

    /**
     * An array of supported filters.
     *
     * @var array
     */
    protected $filters = [
        'blur',
        'brightness',
        'colorize',
        'contrast',
        'crop',
        'encode',
        'fit',
        'flip',
        'gamma',
        'greyscale',
        'heighten',
        'invert',
        'limitColors',
        'pixelate',
        'opacity',
        'resize',
        'rotate',
        'amount',
        'widen',
    ];

    /**
     * Return a new image manager class.
     *
     * @param $image
     * @return ImageManager
     */
    public function make($image)
    {
        $instance = new self;

        $instance->setImage($this->locate($image));

        return $instance;
    }

    /**
     * Publish an image.
     *
     * @param null $path
     */
    protected function publish($path)
    {
        $directory = dirname(public_path($path));

        if (!\File::isDirectory($directory)) {
            \File::makeDirectory($directory, 777, true);
        }

        $image = parent::make($this->image);

        foreach ($this->applied[$this->image] as $method => $arguments) {
            if (method_exists($image, $method)) {
                $image = call_user_func_array([$image, $method], $arguments);
            }
        }

        $image->save($path);
    }

    /**
     * Locate the path of an image.
     *
     * @param $image
     */
    protected function locate($image)
    {
        if (strpos($image, '::') !== false) {
            list($namespace, $path) = explode('::', $image);
        } else {
            $namespace = 'theme';
            $path      = $image;
        }

        return $this->namespaces[$namespace] . '/' . $path;
    }

    /**
     * Pipe the input and return it's public path.
     *
     * @return string
     */
    protected function pipe()
    {
        $file = app('files');

        $application = app('streams.application');

        $filename = $this->filename();

        $path = 'assets/' . $application->getReference() . '/' . $filename;

        if (!$file->exists($path) or isset($_GET['_compile'])) {
            $this->publish($path);
        }

        return $path;
    }

    /**
     * Return the filename of an image based on the image and it's filters.
     *
     * @return string
     */
    protected function filename()
    {
        return hashify([$this->image, $this->applied[$this->image]]) . '.' . \File::extension($this->image);
    }

    /**
     * Blur the image.
     *
     * @param int $pixels
     * @return $this
     */
    public function blur($pixels)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Adjust the brightness of the image.
     *
     * @param int $level
     * @return $this
     */
    public function brightness($level)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Adjust the color of the image.
     *
     * @param $red
     * @param $green
     * @param $blue
     * @return $this
     */
    public function colorize($red, $green, $blue)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Adjust the contrast of the image.
     *
     * @param $level
     * @return $this
     */
    public function contrast($level)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Crop the image.
     *
     * @param      $width
     * @param      $height
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function crop($width, $height, $x = null, $y = null)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Encode the image as a different mime.
     *
     * @param $mime
     * @return $this
     */
    public function encode($mime)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Fit the image to the provided dimensions.
     *
     * @param      $width
     * @param null $height
     * @return $this
     */
    public function fit($width, $height = null)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Flip the image.
     *
     * @param $direction
     * @return $this
     */
    public function flip($direction)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Adjust the level of the image.
     *
     * @param $level
     * @return $this
     */
    public function gamma($level)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Convert the image to greyscale.
     *
     * @return $this
     */
    public function greyscale()
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Heighten the image.
     *
     * @param $height
     * @return $this
     */
    public function heighten($height)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Invert the image.
     *
     * @return $this
     */
    public function invert()
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Limit the colors of the image.
     *
     * @param      $count
     * @param null $matte
     * @return $this
     */
    public function limitColors($count, $matte = null)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Pixelate the image.
     *
     * @param $size
     * @return $this
     */
    public function pixelate($size)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Adjust the opacity of the image.
     *
     * @param $opacity
     * @return $this
     */
    public function opacity($opacity)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Resize the image.
     *
     * @param $width
     * @param $height
     * @return $this
     */
    public function resize($width, $height)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Rotate the image counter-clock-wise.
     *
     * @param      $angle
     * @param null $background
     * @return $this
     */
    public function rotate($angle, $background = null)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Sharpen the image.
     *
     * @param $amount
     * @return $this
     */
    public function amount($amount)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Widen the image.
     *
     * @param $width
     */
    public function widen($width)
    {
        return $this->addFilter(__FUNCTION__, func_get_args());
    }

    /**
     * Return the path to the image.
     *
     * @return string
     */
    public function path()
    {
        return $this->pipe();
    }

    /**
     * Get the URL of an image.
     *
     * @param      $identifier
     * @param null $extra
     * @param null $secure
     * @return string
     */
    public function url($extra = [], $secure = null)
    {
        $path = $this->pipe();

        return \URL::to($path, $extra, $secure or app('request')->isSecure());
    }

    /**
     * Render an image tag.
     *
     * @param null  $alt
     * @param array $attributes
     * @param null  $secure
     * @return string
     */
    public function image($alt = null, $attributes = [], $secure = null)
    {
        $path = $this->pipe();

        return app('html')->image($path, $alt, $attributes, $secure or app('request')->isSecure());
    }

    /**
     * Set the namespaces property.
     *
     * @param $namespaces
     * @return $this
     */
    public function setNamespaces($namespaces)
    {
        foreach ($namespaces as $binding => $path) {
            $this->addNamespace($binding, $path);
        }

        return $this;
    }

    /**
     * Add a single namespace path by it's binding.
     *
     * @param $binding
     * @param $path
     */
    public function addNamespace($binding, $path)
    {
        $this->namespaces[$binding] = $path;

        return $this;
    }

    /**
     * Set the image.
     *
     * @param $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        $this->applied[$image] = [];

        return $this;
    }

    /**
     * Add some filters for the current image.
     *
     * @param $method
     * @param $arguments
     * @return $this
     */
    public function addFilter($method, $arguments)
    {
        $this->applied[$this->image][$method] = $arguments;

        return $this;
    }

    /**
     * Get the supported filters.
     *
     * @return array
     */
    public function getSupportedFilters()
    {
        return $this->filters;
    }
}
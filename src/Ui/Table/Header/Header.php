<?php namespace Anomaly\Streams\Platform\Ui\Table\Header;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Ui\Table\Header\Contract\HeaderInterface;

/**
 * Class Header
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table\Header
 */
class Header implements HeaderInterface
{

    /**
     * The stream object.
     *
     * @var null
     */
    protected $stream = null;

    /**
     * The header text.
     *
     * @var null
     */
    protected $text;

    /**
     * Create a new Header instance.
     *
     * @param null $text
     */
    public function __construct($text = null)
    {
        $this->text = $text;
    }

    /**
     * Return the view data.
     *
     * @param array $arguments
     * @return array
     */
    public function viewData(array $arguments = [])
    {
        $text = $this->getText();

        if ($this->stream && is_string($text)) {
            $text = $this->getTextFromField($text);
        }

        $text = trans($text);

        return compact('text');
    }

    /**
     * Set the stream object.
     *
     * @param StreamInterface $stream
     * @return $this
     */
    public function setStream(StreamInterface $stream)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Set the text.
     *
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the text from a field.
     *
     * @param $text
     * @return mixed
     */
    protected function getTextFromField($text)
    {
        if ($field = $this->stream->getField($text)) {
            return $field->getName();
        }

        return $text;
    }
}

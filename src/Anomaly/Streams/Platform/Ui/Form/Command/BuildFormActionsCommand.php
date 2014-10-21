<?php namespace Anomaly\Streams\Platform\Ui\Form\Command;

use Anomaly\Streams\Platform\Ui\Form\FormUi;

class BuildFormActionsCommand
{
    protected $ui;

    function __construct(FormUi $ui)
    {
        $this->ui = $ui;
    }

    /**
     * @return mixed
     */
    public function getUi()
    {
        return $this->ui;
    }
}
 
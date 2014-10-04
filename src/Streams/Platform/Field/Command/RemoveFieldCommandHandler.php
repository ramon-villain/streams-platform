<?php namespace Streams\Platform\Field\Command;

use Streams\Platform\Field\FieldModel;
use Streams\Platform\Traits\DispatchableTrait;
use Streams\Platform\Contract\CommandInterface;

class RemoveFieldCommandHandler implements CommandInterface
{
    use DispatchableTrait;

    /**
     * The field model.
     *
     * @var \Streams\Platform\Field\Model\FieldModel
     */
    protected $field;

    /**
     * Create a new InstallFieldCommandHandler instance.
     *
     * @param FieldModel $field
     */
    function __construct(FieldModel $field)
    {
        $this->field = $field;
    }

    /**
     * Handle the command.
     *
     * @param $command
     * @return $this|mixed
     */
    public function handle($command)
    {
        $field = $this->field->remove(
            $command->getNamespace(),
            $command->getSlug()
        );

        if ($field) {
            $this->dispatchEventsFor($field);

            return $field;
        }

        return false;
    }
}
 
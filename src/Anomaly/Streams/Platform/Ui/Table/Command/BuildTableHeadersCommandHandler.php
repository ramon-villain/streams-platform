<?php namespace Anomaly\Streams\Platform\Ui\Table\Command;

use Anomaly\Streams\Platform\Ui\Table\TableUi;
use Anomaly\Streams\Platform\Entry\EntryInterface;
use Anomaly\Streams\Platform\Ui\Table\TableUtility;

/**
 * Class BuildTableHeadersCommandHandler
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table\Command
 */
class BuildTableHeadersCommandHandler
{

    /**
     * The table utility object.
     *
     * @var
     */
    protected $utility;

    /**
     * Create a new BuildTableHeadersCommandHandler instance.
     *
     * @param TableUtility $utility
     */
    function __construct(TableUtility $utility)
    {
        $this->utility = $utility;
    }

    /**
     * Handle the command.
     *
     * @param BuildTableHeadersCommand $command
     * @return array
     */
    public function handle(BuildTableHeadersCommand $command)
    {
        $ui = $command->getUi();

        $columns = [];

        foreach ($ui->getColumns() as $column) {

            /**
             * If the column is a string
             * then assume it's the field.
             */
            if (is_string($column)) {

                $column = ['field' => $column];

            }

            // Evaluate everything in the array.
            // All closures are gone now.
            $column = $this->utility->evaluate($column, [$ui]);

            // Build out our required data.
            $heading = $this->getHeading($column, $ui);

            $columns[] = compact('heading');

        }

        return $columns;
    }

    /**
     * Get the heading.
     *
     * @param $column
     * @param $ui
     * @return null|string
     */
    protected function getHeading($column, TableUi $ui)
    {
        $heading = trans(evaluate_key($column, 'heading', null, [$ui]));

        if (!$heading and $entry = $ui->getModel() and $entry instanceof EntryInterface) {

            $heading = $this->getHeadingFromField($column, $entry);

        }

        if (!$heading) {

            $this->guessHeading($column, $ui);

        }

        return $heading;
    }

    /**
     * Get the heading from a field.
     *
     * @param $column
     * @param $entry
     * @return null
     */
    protected function getHeadingFromField($column, EntryInterface $entry)
    {
        $parts = explode('.', $column['field']);

        if ($name = $entry->getFieldName($parts[0])) {

            return $name;

        }

        return null;
    }

    /**
     * Make our best guess at the heading.
     *
     * @param $column
     * @param $ui
     * @return mixed|null|string
     */
    protected function guessHeading($column, $ui)
    {
        $heading = evaluate_key($column, 'heading', evaluate_key($column, 'field', null), [$ui]);

        $translated = trans($heading);

        if ($translated == $heading) {

            $heading = humanize($heading);

        } else {

            $heading = $translated;

        }

        return $heading;
    }

}
 
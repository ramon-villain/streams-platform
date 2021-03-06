<?php namespace Anomaly\Streams\Platform\Ui\Table\Command;

use Anomaly\Streams\Platform\Ui\Table\Event\TableDataLoadedEvent;
use Laracasts\Commander\CommanderTrait;

/**
 * Class MakeTableCommandHandler
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table\Command
 */
class MakeTableCommandHandler
{

    use CommanderTrait;

    /**
     * Make and set all of the view data on the table.
     *
     * @param MakeTableCommand $command
     */
    public function handle(MakeTableCommand $command)
    {
        $builder = $command->getBuilder();
        $table   = $builder->getTable();

        $args = compact('builder');

        $this->execute('Anomaly\Streams\Platform\Ui\Table\Command\SetTableDataCommand', $args);
        $this->execute('Anomaly\Streams\Platform\Ui\Table\Row\Command\SetRowDataCommand', $args);
        $this->execute('Anomaly\Streams\Platform\Ui\Table\View\Command\SetViewDataCommand', $args);
        $this->execute('Anomaly\Streams\Platform\Ui\Table\Header\Command\SetHeaderDataCommand', $args);
        $this->execute('Anomaly\Streams\Platform\Ui\Table\Filter\Command\SetFilterDataCommand', $args);
        $this->execute('Anomaly\Streams\Platform\Ui\Table\Action\Command\SetActionDataCommand', $args);
        $this->execute('Anomaly\Streams\Platform\Ui\Table\Command\SetPaginationDataCommand', $args);

        app('events')->fire('streams::table.data.loaded', new TableDataLoadedEvent($table));

        $table->setContent(view($table->getView(), $table->getData()));
    }
}

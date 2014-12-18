<?php namespace Anomaly\Streams\Platform\Ui\Table\Filter;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Laracasts\Commander\CommanderTrait;

/**
 * Class FilterBuilder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table\Filter
 */
class FilterBuilder
{

    use CommanderTrait;

    /**
     * The filter interpreter.
     *
     * @var FilterInterpreter
     */
    protected $interpreter;

    /**
     * The table evaluator.
     *
     * @var FilterEvaluator
     */
    protected $evaluator;

    /**
     * The filter factory.
     *
     * @var FilterFactory
     */
    protected $factory;

    /**
     * The filter loader.
     *
     * @var FilterLoader
     */
    protected $loader;

    /**
     * Create a new FilterBuilder instance.
     *
     * @param FilterInterpreter $interpreter
     * @param FilterEvaluator   $evaluator
     * @param FilterFactory     $factory
     * @param FilterLoader      $loader
     */
    function __construct(
        FilterInterpreter $interpreter,
        FilterEvaluator $evaluator,
        FilterFactory $factory,
        FilterLoader $loader
    ) {
        $this->loader      = $loader;
        $this->factory     = $factory;
        $this->interpreter = $interpreter;
        $this->evaluator   = $evaluator;
    }

    /**
     * Load filters onto a collection.
     *
     * @param TableBuilder $builder
     */
    public function load(TableBuilder $builder)
    {
        $table   = $builder->getTable();
        $filters = $table->getFilters();

        foreach ($builder->getFilters() as $key => $parameters) {

            $parameters = $this->interpreter->standardize($key, $parameters);
            $parameters = $this->evaluator->process($parameters, $builder);

            $parameters['stream'] = $table->getStream();

            $filter = $this->factory->make($parameters);

            $this->loader->load($filter, $parameters);

            $filters->put($filter->getSlug(), $filter);
        }
    }
}
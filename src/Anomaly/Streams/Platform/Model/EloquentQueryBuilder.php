<?php namespace Anomaly\Streams\Platform\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class EloquentQueryBuilder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Model
 */
class EloquentQueryBuilder extends Builder
{

    protected $cacheKey = null;

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = array('*'))
    {
        $this->rememberIndex();

        if ($this->cacheKey) {

            return app('cache')->remember(
                $this->cacheKey,
                $this->model->getCacheMinutes(),
                function () use ($columns) {

                    return parent::get($columns);
                }
            );
        }

        return parent::get($columns);
    }

    protected function rememberIndex()
    {
        if ($cacheMinutes = $this->model->getCacheMinutes()) {

            $this->indexCacheCollection();
        }

        return $this;
    }

    /**
     * Index cache collection
     *
     * @return object
     */
    protected function indexCacheCollection()
    {
        app('streams.cache.collection')
            ->make([$this->getCacheKey()])
            ->setKey($this->model->getCacheCollectionKey())
            ->index();

        return $this;
    }

    /**
     * Get the unique cache key for the query.
     *
     * @return string
     */
    public function getCacheKey()
    {
        if ($this->cacheKey) {

            return $this->cacheKey;
        }

        $name = $this->model->getConnectionName();

        return $this->cacheKey = md5($name . $this->toSql() . serialize($this->getBindings()));
    }

    /**
     * Get fresh models / disable cache
     *
     * @param  boolean $fresh
     * @return object
     */
    public function fresh($fresh = true)
    {
        if ($fresh) {

            $this->model->setCacheMinutes(0);
        }

        return $this;
    }
}
 
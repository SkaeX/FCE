<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 10/17/2015
 * Time: 2:36 PM
 */

namespace Fce\Repositories;

use Fce\Transformers\Transformable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Repository
{
    use Transformable;

    /**
     * The model registered on the repository.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create a new repository instance
     */
    public function __construct()
    {
        $this->model = $this->getModel();
    }

    /**
     * Get an instance of the registered model
     *
     * @return Model
     */
    abstract protected function getModel();

    /**
     * Create and persist a new model
     *
     * @param array $attributes
     * @return static
     * @throws \InvalidArgumentException
     */
    protected function create(array $attributes)
    {
        try {
            return $this->model->create($attributes);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Wrong or incomplete set of attributes provided');
        }
    }

    /**
     * Return a paginated list of all the available models
     *
     * @param int $limit
     * @param array $columns
     * @return mixed
     */
    public function all($limit = 15, array $columns = ['*'])
    {
        return self::transform($this->model->limit($limit)->get($columns));
    }

    /**
     * Find a model by its id
     *
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws ModelNotFoundException
     */
    protected function find($id, array $columns = ['*'])
    {
        return self::transform($this->model->findOrFail($id, $columns));
    }

    /**
     * Finds and returns one, all or a paginated list of the models with the specified parameters (field and value)
     *
     * @param array $params
     * @param int $limit
     * @param int $page
     * @param array $columns
     * @param array $with
     * @return mixed
     * @throws ModelNotFoundException
     */
    protected function findBy(array $params, $limit = 15, $page = 1, array $columns = ['*'], array $with = [])
    {
        // Model to use for the method chaining
        $items = $this->model;

        foreach($params as $field => $value) {
            $items = $items->where($field, 'like', '%' . $value . '%')->with($with);
        }

        // Returns one, all or a paginated list of items
        switch ($limit) {
            case 'one':
                $items = $items->first($columns);
                break;

            case 'all':
                $items = $items->get($columns);
                break;

            default:
                $items = $items->paginate($limit, $columns, 'page', $page);
        }

        if (is_null($items) || !count($items)) {
            throw new ModelNotFoundException('Could not find the specified model');
        }

        return self::transform($items);
    }

    /**
     * Update the model in the database
     *
     * @param $id
     * @param array $attributes
     * @return mixed
     * @throws ModelNotFoundException
     */
    protected function update($id, array $attributes)
    {
        // Allows the calling method to pass the model directly if it has already been found
        // to prevent another database lookup. This might be removed eventually
        if ($id instanceof Model) {
            $model = $id;
            return $model->update($attributes);
        }

        return $this->model->findOrFail($id)->update($attributes);
    }
}

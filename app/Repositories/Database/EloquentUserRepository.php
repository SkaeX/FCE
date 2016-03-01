<?php

/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 10/29/2015
 * Time: 7:51 PM.
 */
namespace Fce\Repositories\Database;

use Fce\Models\User;
use Fce\Repositories\Repository;
use Fce\Repositories\Contracts\UserRepository;
use Fce\Transformers\UserTransformer;

class EloquentUserRepository extends Repository implements UserRepository
{
    /**
     * Create a new repository instance.
     *
     * @param User $model
     * @param UserTransformer $transformer
     */
    public function __construct(User $model, UserTransformer $transformer)
    {
        $this->model = $model;
        $this->transformer = $transformer;
    }

    /**
     * Get a paginated list of all users.
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->all();
    }

    /**
     * Get all users that belong to a particular school.
     *
     * @param $schoolId
     * @return array
     */
    public function getUsersBySchool($schoolId)
    {
        return $this->findBy(['school_id' => $schoolId]);
    }

    /**
     * Get a single section by its id.
     *
     * @param $id
     * @return array
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Create a new user from the specified attributes.
     *
     * @param array $attributes
     * @return array
     */
    public function createUser(array $attributes)
    {
        return $this->create($attributes);
    }

    /**
     * Update a user's attributes.
     *
     * @param $id
     * @param array $attributes
     * @return boolean
     */
    public function updateUser($id, array $attributes)
    {
        return $this->update($id, $attributes);
    }

    /**
     * Delete a user to prevent access to the system.
     *
     * @param $id
     * @return boolean
     */
    public function deleteUser($id)
    {
        return $this->model->findOrFail($id)->delete() == 1;
    }

    /**
     * Disables user to prevent access to the system.
     * 
     * @param $id [id of the the user to be disabled]
     * @return bool
     */
    public function disableUser($id)
    {
        return $this->update($id, ['active' => false]);
    }
    /**
     * Enables user access to the system.
     * 
     * @param $id [id of the the user to be disabled]
     * @return bool
     */
    public function enableUser($id)
    {
        return $this->update($id, ['active' => true]);
    }
}
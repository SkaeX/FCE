<?php
/**
 * Created by BrainMaestro
 * Date: 19/2/2016
 * Time: 8:14 PM
 */

namespace Fce\Repositories\Database;

use Fce\Models\Key;
use Fce\Repositories\Contracts\KeyRepository;
use Fce\Repositories\Repository;
use Fce\Transformers\KeyTransformer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SQLKeyRepository extends Repository implements KeyRepository
{
    /**
     * Maximum number of tries allowed for failing key creation
     */
    const MAX_TRIES = 3;

    /**
     * The transformer registered on the repository.
     *
     * @var \League\Fractal\TransformerAbstract
     */
    protected static $transformer = KeyTransformer::class;

    /**
     * Get an instance of the registered model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel()
    {
        return new Key;
    }

    /**
     * Gets all keys by the section they belong to
     *
     * @param $sectionId
     * @return mixed
     */
    public function getKeysBySection($sectionId)
    {
        return $this->findBy(['section_id' => $sectionId]);
    }

    /**
     * Create keys for a section
     *
     * @param array $section
     * @return array
     * @throws QueryException
     */
    public function createKeys(array $section)
    {
        $keys = [];

        DB::beginTransaction();
        for ($i = $tries = 0; $i < $section['enrolled']; $i++) {
            try {
                $key = $this->create([
                    'value' => strtoupper(str_random(6)),
                    'section_id' => $section['id']
                ]);

                $keys[] = $key['data'];
            } catch (QueryException $e) {
                // Throws a QueryException after too many tries
                if ($tries >= self::MAX_TRIES) {
                    DB::rollBack();
                    throw new QueryException($e->getSql(), $e->getBindings(), $e->getPrevious());
                }

                ++$tries; // Keep track of number of tries
                --$i; // So that the current iteration of the loop runs again
            }
        }
        DB::commit();

        return $keys;
    }

    /**
     * Set a particular key as given out
     *
     * @param $id
     * @return bool
     */
    public function setGivenOut($id)
    {
        return $this->update($id, ['given_out' => true]);
    }

    /**
     * Set a particular key as used
     *
     * @param $id
     * @return bool
     */
    public function setUsed($id)
    {
        return $this->update($id, ['used' => true]);
    }

    /**
     * Delete the keys that belong to a section
     *
     * @param $sectionId
     * @return bool
     */
    public function deleteKeys($sectionId)
    {
        $this->model->where('section_id', $sectionId)->delete();

        return true;
    }
}
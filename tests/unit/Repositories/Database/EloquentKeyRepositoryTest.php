<?php

use Fce\Repositories\Database\EloquentKeyRepository;

/**
 * Created by BrainMaestro
 * Date: 19/2/2016
 * Time: 8:14 PM
 */
class EloquentKeyRepositoryTest extends TestCase
{
    protected $repository;

    protected $section;
    protected $key;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new EloquentKeyRepository(
            new \Fce\Models\Key,
            new \Fce\Transformers\KeyTransformer
        );
        $this->key = factory(Fce\Models\Key::class)->create();
    }

    public function testGetKeysBySection()
    {
        $key = $this->repository->getKeysBySection($this->key->section->id);

        $this->assertCount(1, $key['data']);

        $this->assertEquals([$this->repository->transform($this->key)['data']], $key['data']);

        $section = factory(Fce\Models\Section::class)->create();
        $keys = factory(Fce\Models\Key::class, 5)->create(['section_id' => $section->id]);
        $keys = $this->repository->transform($keys)['data'];

        $allKeys = $this->repository->getKeysBySection($section->id);

        $this->assertCount(count($keys), $allKeys['data']);
        $this->assertEquals($keys, $allKeys['data']);
    }

    public function testCreateKeys()
    {
        $keys = $this->repository->createKeys($this->key->section->toArray());

        $this->assertCount((int) $this->key->section->enrolled, $keys);
    }

    public function testSetGivenOut()
    {
        $this->assertTrue($this->repository->setGivenOut($this->key->id));

        $key = $this->repository->transform($this->key->fresh());

        $this->assertTrue($key['data']['given_out']);
    }

    public function testSetUsed()
    {
        $this->assertTrue($this->repository->setUsed($this->key->id));

        $key = $this->repository->transform($this->key->fresh());

        $this->assertTrue($key['data']['used']);
    }

    public function testDeleteKeys()
    {
        $this->assertTrue($this->repository->deleteKeys($this->key->section->id));

        $this->setExpectedException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->getKeysBySection($this->key->section->toArray()['id']);
    }
}
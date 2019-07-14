<?php

namespace TestMonitor\Incrementable\Test;

use Illuminate\Database\Eloquent\SoftDeletes;
use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;

class AddIncrementableUsingSoftDeletesTest extends TestCase
{
    /**
     * @var \TestMonitor\Incrementable\Test\Models\Record
     */
    protected $record;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabaseWithSoftDeletes();

        $this->record = new class() extends Record {
            use Incrementable, SoftDeletes;

            protected $incrementable = 'code';
        };
    }

    /** @test */
    public function it_will_skip_a_code_that_was_soft_deleted()
    {
        $firstRecord = new $this->record();
        $firstRecord->save();

        $secondRecord = new $this->record();
        $secondRecord->save();
        $secondRecord->delete();

        $thirdRecord = new $this->record();
        $thirdRecord->save();

        $this->assertEquals($firstRecord->code, 1);
        $this->assertEquals($thirdRecord->code, 3);
    }
}

<?php

namespace TestMonitor\Incrementable\Test;

use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;

class AddIncrementableTest extends TestCase
{
    /**
     * @var \TestMonitor\Incrementable\Test\Models\Record
     */
    protected $record;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->record = new class() extends Record {
            use Incrementable;

            protected $incrementable = 'code';
        };
    }

    /** @test */
    public function it_will_start_counting_the_first_record()
    {
        $record = new $this->record();
        $record->save();

        $this->assertEquals($record->code, 1);
    }

    /** @test */
    public function it_will_count_the_second_record()
    {
        $firstRecord = new $this->record();
        $firstRecord->save();
        $secondRecord = new $this->record();
        $secondRecord->save();

        $this->assertEquals($firstRecord->code, 1);
        $this->assertEquals($secondRecord->code, 2);
    }

    /** @test */
    public function it_will_count_the_hundredth_record()
    {
        collect(range(1, 99))->each(function () {
            $record = new $this->record();
            $record->save();
        });

        $record = new $this->record();
        $record->save();

        $this->assertEquals($record->code, 100);
    }
}

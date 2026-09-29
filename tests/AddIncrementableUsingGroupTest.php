<?php

namespace TestMonitor\Incrementable\Test;

use PHPUnit\Framework\Attributes\Test;
use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;

final class AddIncrementableUsingGroupTest extends TestCase
{
    /**
     * @var Record
     */
    protected $record;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->record = new class extends Record
        {
            use Incrementable;

            protected $incrementable = 'code';

            protected $incrementableGroup = ['project', 'group'];
        };
    }

    #[Test]
    public function it_will_start_counting_the_first_record()
    {
        $record = new $this->record;
        $record->project = 1;
        $record->group = 1;
        $record->save();

        $this->assertEquals(1, $record->code);
    }

    #[Test]
    public function it_will_count_the_second_record()
    {
        $firstRecord = new $this->record;
        $firstRecord->project = 1;
        $firstRecord->group = 1;
        $firstRecord->save();

        $secondRecord = new $this->record;
        $secondRecord->project = 1;
        $secondRecord->group = 1;
        $secondRecord->save();

        $this->assertEquals(1, $firstRecord->code);
        $this->assertEquals(2, $secondRecord->code);
    }

    #[Test]
    public function it_will_restart_counting_for_a_new_project()
    {
        $firstRecord = new $this->record;
        $firstRecord->project = 1;
        $firstRecord->group = 1;
        $firstRecord->save();

        $secondRecord = new $this->record;
        $secondRecord->project = 2;
        $secondRecord->group = 1;
        $secondRecord->save();

        $this->assertEquals(1, $firstRecord->code);
        $this->assertEquals(1, $secondRecord->code);
    }

    #[Test]
    public function it_will_continue_counting_for_a_new_project()
    {
        $firstRecord = new $this->record;
        $firstRecord->project = 1;
        $firstRecord->group = 1;
        $firstRecord->save();

        $secondRecord = new $this->record;
        $secondRecord->project = 2;
        $secondRecord->group = 1;
        $secondRecord->save();

        $thirdRecord = new $this->record;
        $thirdRecord->project = 2;
        $thirdRecord->group = 1;
        $thirdRecord->save();

        $this->assertEquals(1, $firstRecord->code);
        $this->assertEquals(1, $secondRecord->code);
        $this->assertEquals(2, $thirdRecord->code);
    }
}

<?php

namespace TestMonitor\Incrementable\Test;

use PHPUnit\Framework\Attributes\Test;
use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;

final class AddIncrementableTest extends TestCase
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
        };
    }

    #[Test]
    public function it_will_start_counting_the_first_record()
    {
        $record = new $this->record;
        $record->save();

        $this->assertEquals(1, $record->code);
    }

    #[Test]
    public function it_will_count_the_second_record()
    {
        $firstRecord = new $this->record;
        $firstRecord->save();

        $secondRecord = new $this->record;
        $secondRecord->save();

        $this->assertEquals(1, $firstRecord->code);
        $this->assertEquals(2, $secondRecord->code);
    }

    #[Test]
    public function it_will_count_the_hundredth_record()
    {
        collect(range(1, 99))->each(function () {
            $record = new $this->record;
            $record->save();
        });

        $record = new $this->record;
        $record->save();

        $this->assertEquals(100, $record->code);
    }
}

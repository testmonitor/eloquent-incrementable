<?php

namespace TestMonitor\Incrementable\Test;

use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\Attributes\Test;
use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;

final class AddIncrementableUsingSoftDeletesTest extends TestCase
{
    /**
     * @var Record
     */
    protected $record;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabaseWithSoftDeletes();

        $this->record = new class extends Record
        {
            use Incrementable, SoftDeletes;

            protected $incrementable = 'code';
        };
    }

    #[Test]
    public function it_will_skip_a_code_that_was_soft_deleted()
    {
        $firstRecord = new $this->record;
        $firstRecord->save();

        $secondRecord = new $this->record;
        $secondRecord->save();
        $secondRecord->delete();

        $thirdRecord = new $this->record;
        $thirdRecord->save();

        $this->assertEquals(1, $firstRecord->code);
        $this->assertEquals(3, $thirdRecord->code);
    }
}

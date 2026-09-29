<?php

namespace TestMonitor\Incrementable\Test;

use PHPUnit\Framework\Attributes\Test;
use TestMonitor\Incrementable\Exceptions\MissingIncrementableDefinition;
use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;

class WithoutIncrementableDefinitionTest extends TestCase
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
        };
    }

    #[Test]
    public function it_will_throw_an_exception_when_defition_is_missing()
    {
        $this->expectException(MissingIncrementableDefinition::class);

        $record = new $this->record;
        $record->save();
    }
}

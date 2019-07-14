<?php

namespace TestMonitor\Incrementable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use TestMonitor\Incrementable\Exceptions\MissingIncrementableDefinition;

trait Incrementable
{
    /**
     * Boot the Incrementable trait for a model.
     *
     * @return void
     */
    public static function bootIncrementable()
    {
        static::creating(function ($model) {
            $model->setIncrementableValue();
        });
    }

    /**
     * Determine and set the increment value.
     */
    public function setIncrementableValue()
    {
        $incrementableField = $this->getIncrementableField();

        $this->{$incrementableField} = $this->getNextIncrementableValue();
    }

    /**
     * Get the increment field, throws an exception when missing.
     *
     * @return string
     *
     * @throws \TestMonitor\Incrementable\Exceptions\MissingIncrementableDefinition
     */
    protected function getIncrementableField(): string
    {
        if (! property_exists($this, 'incrementable')) {
            throw MissingIncrementableDefinition::create(get_class($this));
        }

        return $this->incrementable;
    }

    /**
     * Gets the next available value for a new model.
     *
     * @return int
     */
    protected function getNextIncrementableValue(): int
    {
        return $this->getHighestIncrementableValue() + 1;
    }

    /**
     * Gets the highest available value currently available.
     *
     * @return int
     */
    protected function getHighestIncrementableValue(): int
    {
        $incrementableField = $this->getIncrementableField();

        return (int) $this->buildIncrementableQuery()
            ->where(function (Builder $query) {
                $this->buildIncrementableGroupQuery($query);
            })
            ->max($incrementableField);
    }

    /**
     * Build incrementable query group.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildIncrementableGroupQuery(Builder $query)
    {
        if (! property_exists($this, 'incrementableGroup')) {
            return $query;
        }

        collect($this->incrementableGroup)->each(function ($group) use ($query) {
            $query->where($group, '=', $this->$group);
        });
    }

    /**
     * Build incrementable query. Supports soft-deletes.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildIncrementableQuery()
    {
        if (collect(class_uses(__CLASS__))->contains(SoftDeletes::class)) {
            return static::query()->withTrashed();
        }

        return static::query();
    }
}

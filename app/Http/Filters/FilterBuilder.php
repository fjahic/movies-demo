<?php

declare(strict_types=1);

namespace App\Http\Filters;

use App\Exceptions\InvalidFilterException;
use Illuminate\Database\Eloquent\Builder;

class FilterBuilder
{
    /** @var array<string, class-string> */
    private array $filters = [
        'director' => DirectorFilter::class,
        'release_year' => ReleaseYearFilter::class,
        'genre' => GenreFilter::class,
    ];

    public function __construct(
        private Builder $builder,
    ) {}

    /**
     * @throws InvalidFilterException
     */
    public function apply(array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            $this->makeFilter($key)->apply($this->builder, $value);
        }

        return $this->builder;
    }

    /**
     * @throws InvalidFilterException
     */
    private function makeFilter(string $filterKey): FilterInterface
    {
        if (!$this->filterExists($filterKey)) {
            throw new InvalidFilterException('Provided filter was not found in configuration');
        }

        return new $this->filters[$filterKey]();
    }

    private function filterExists(string $filterKey): bool
    {
        return array_key_exists($filterKey, $this->filters) && class_exists($this->filters[$filterKey]);
    }
}
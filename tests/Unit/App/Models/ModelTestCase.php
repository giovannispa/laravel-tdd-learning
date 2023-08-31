<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function expectedTraits(): array;
    abstract protected function expectedFillables(): array;
    abstract protected function expectedCasts(): array;

    /**
     * Exemplo de teste em traits
     *
     * @return void
     */
    public function test_traits(): void
    {
        $traits = array_keys(class_uses($this->model()));
        $this->assertEquals($this->expectedTraits(), $traits);
    }

    /**
     * Exemplo de teste em fillable.
     *
     * @return void
     */
    public function test_fillables()
    {
        $fillables = $this->model()->getFillable();
        $this->assertEquals($this->expectedFillables(), $fillables);
    }

    /**
     * Exemplo de teste em casts.
     *
     * @return void
     */
    public function test_casts()
    {
        $casts = $this->model()->getCasts();
        $this->assertEquals($this->expectedCasts(), $casts);
    }
}

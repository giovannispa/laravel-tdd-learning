<?php

namespace App\Repository\Contracts;

interface PaginateResponseInterface
{
    public function total(): int;
    public function items(): array;
    public function currentPage(): int;
    public function perPage(): int;
    public function firstPage(): int;
    public function lastPage(): int;
}

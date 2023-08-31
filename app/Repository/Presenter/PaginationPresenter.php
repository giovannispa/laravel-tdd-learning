<?php

namespace App\Repository\Presenter;

use App\Repository\Contracts\PaginateResponseInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationPresenter implements PaginateResponseInterface
{
    protected LengthAwarePaginator $paginator;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Método que retorna o total de itens na lista.
     *
     * @return int
     */
    public function total(): int
    {
        return (int)$this->paginator->total() ?? 0;
    }

    /**
     * Método que retorna os itens da lista.
     *
     * @return array
     */
    public function items(): array
    {
        return $this->paginator->items();
    }

    /**
     * Método que retorna qual a página atual da paginação.
     *
     * @return int
     */
    public function currentPage(): int
    {
        return (int)$this->paginator->currentPage() ?? 1;
    }

    /**
     * Método que retorna a quantidade de itens por página.
     *
     * @return int
     */
    public function perPage(): int
    {
        return (int)$this->paginator->perPage() ?? 1;
    }

    /**
     * Método que retorna a primeira página da lista.
     *
     * @return int
     */
    public function firstPage(): int
    {
        return (int)$this->paginator->firstItem() ?? 1;
    }

    /**
     * Método que retorna a ultima página da lista.
     *
     * @return int
     */
    public function lastPage(): int
    {
        return (int)$this->paginator->lastPage() ?? 1;
    }
}

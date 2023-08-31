<?php

namespace App\Repository\Eloquent;

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Repository\Contracts\PaginateResponseInterface;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Presenter\PaginationPresenter;

class UserRepository implements UserRepositoryInterface
{
    public User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Método que retorna todos os usuários do banco.
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }

    /**
     * Método que cria um novo usuário no sistema.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * Método que localiza um usuário por e-mail.
     *
     * @param string $email
     * @return object|null
     * @throws NotFoundException
     */
    public function find(string $email): object
    {
        if(!$user = $this->model->where('email',$email)->first()){
            throw new NotFoundException("User Not Found");
        }

        return $user;
    }

    /**
     * Método que atualiza um usuário.
     *
     * @param string $email
     * @param array $data
     * @return object
     */
    public function update(string $email, array $data): object
    {
        $user = $this->find($email);

        $user->update($data);
        return $user;
    }

    /**
     * Método que deleta um usuário.
     *
     * @param string $email
     * @return bool
     */
    public function delete(string $email): bool
    {
        $user = $this->find($email);

        return $user->delete();
    }

    /**
     * Método que retorna todos os usuários do banco com paginação.
     *
     * @return array
     */
    public function paginate(int $page = 1): PaginateResponseInterface
    {
        return new PaginationPresenter($this->model->paginate());
    }
}

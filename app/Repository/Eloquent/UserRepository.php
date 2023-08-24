<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;

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
     */
    public function find(string $email): ?object
    {
        return $this->model->where('email',$email);
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
}

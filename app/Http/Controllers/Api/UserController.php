<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Action principal da api de listagem de usuários.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = collect($this->repository->findAll());

        return UserResource::collection($users);
    }

    /**
     * Action que gerencia toda listagem de usuários por página.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function paginate()
    {
        $response = $this->repository->paginate();
        return UserResource::collection(collect($response->items()))->additional([
            'meta' => [
                'total' => $response->total(),
                'current_page' => $response->currentPage(),
                'last_page' => $response->lastPage(),
                'first_page' => $response->firstPage(),
                'per_page' => $response->perPage()
            ]
        ]);
    }

    /**
     * Action que salva o usuário no banco.
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function store(UserRequest $request)
    {
        $user = $this->repository->create($request->all());

        return new UserResource($user);
    }

    /**
     * Action que retorna o usuário especifico no endpoint.
     *
     * @param $email
     * @return UserResource
     */
    public function show($email)
    {
        $user = $this->repository->find($email);

        return new UserResource(collect($user));
    }

    /**
     * Action que atualiza um usuário existente no banco.
     *
     * @param UserRequest $request
     * @param $email
     * @return UserResource
     */
    public function update(UserRequest $request, $email)
    {
        return new UserResource(collect($this->repository->update($email, $request->all())));
    }

    /**
     * Action que deleta um usuário especifico no banco.
     *
     * @param $email
     * @return \Illuminate\Http\Response
     */
    public function destroy($email)
    {
        $this->repository->delete($email);

        return response()->noContent();
    }
}

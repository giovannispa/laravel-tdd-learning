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

    public function index()
    {
        $users = collect($this->repository->findAll());

        return UserResource::collection($users);
    }

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

    public function store(UserRequest $request)
    {
        $user = $this->repository->create($request->all());

        return new UserResource($user);
    }

    public function show($email)
    {
        $user = $this->repository->find($email);

        return new UserResource(collect($user));
    }

    public function update(UserRequest $request, $email)
    {
        return new UserResource(collect($this->repository->update($email, $request->all())));
    }

    public function destroy($email)
    {
        $this->repository->delete($email);

        return response()->noContent();
    }
}

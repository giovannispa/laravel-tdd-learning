<?php

namespace Tests\Feature\Api;

use App\Exceptions\NotFoundException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
   protected string $endpoint = "/api/users";
   protected string $endpointPaginate = "/api/users/paginate";
   protected string $endpointFindUser = '/api/user/';

    /**
     * Testando a conexão com o endpoint.
     *
     * @return void
     */
    public function test_api(): void
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
    }

    /**
     * Testando pegar vários usuários.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        User::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(20, 'data');
    }

    /**
     * Testando a paginação de usuários.
     *
     * @dataProvider dataProviderPagination
     * @return void
     */
    public function test_paginate(
        int $total,
        int $page,
        int $totalPage,
    ): void
    {
        User::factory()->count($total)->create();

        $response = $this->getJson("{$this->endpointPaginate}?page={$page}");

        $response->assertStatus(200);
        $response->assertJsonCount($totalPage, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ]
        ]);
    }

    /**
     * Testando criar 1 usuário no banco.
     *
     * @dataProvider dataProviderCreateUser
     * @return void
     */
    public function test_create(
        array $payload,
        int $code,
        array $structure
    )
    {
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus($code);
        $response->assertJsonStructure([
            'data' => [
                $structure
            ]
        ]);
    }

    /**
     * Testando a pesquisa por usuário especifico no banco.
     *
     * @return void
     */
    public function test_find()
    {
        $user = User::factory()->create();
        $response = $this->getJson($this->endpointFindUser.$user->email);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
            ]
        ]);
    }

    /**
     * Testando a pesquisa por usuário especifico no banco com falha.
     *
     * @return void
     */
    public function test_find_not_found()
    {
        $response = $this->getJson($this->endpointFindUser . "eqwqwfqwf");
        $response->assertStatus(404);
    }

    /**
     * Testando atualizar 1 usuário existente no banco.
     *
     * @dataProvider dataProviderUpdateUser
     * @param array $payload
     * @param int $code
     * @return void
     */
    public function test_update(
        array $payload,
        int $code
    )
    {
        $user = User::factory()->create();
        $response = $this->putJson("{$this->endpoint}/{$user->email}", $payload);
        $response->assertStatus($code);
    }

    /**
     * Testando deletar usuário.
     *
     * @return void
     */
    public function test_delete()
    {
        $user = User::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$user->email}");
        $response->assertNoContent();
    }

    /**
     * Data Provider de paginação.
     *
     * @return array[]
     */
    public function dataProviderPagination(): array
    {
        return [
            'test total 40 users page 1' => ["total" => 40,"page" => 1, "totalPage" => 15],
            'test total 20 users page 2' => ["total" => 20,"page" => 2, "totalPage" => 5],
            'test total 0 users page 1' => ["total" => 0,"page" => 1, "totalPage" => 0],
        ];
    }

    /**
     * Data Provider de criação de usuário.
     *
     * @return array[]
     */
    public function dataProviderCreateUser(): array
    {
        return [
            'create user with all data' =>[['name' => 'Giovanni', 'email' => 'giovanni@gmail.com', 'password' => bcrypt('12345')], 'code' => 201,
                'structure' => ['name', 'email']],
            'create user without password' =>[['name' => 'Giovanni', 'email' => 'giovanni@gmail.com'], 'code' => 422,
                'structure' => ['name', 'email']]
        ];
    }

    /**
     * Data Provider de atualização de usuário.
     *
     * @return array[]
     */
    public function dataProviderUpdateUser(): array
    {
        return [
            'update email user' => ['payload' => ['name' => 'Giovanni', 'email' => 'giovanni@gmail.com', 'password' => bcrypt('12345')], 'code' => 200],
            'update user without password' =>['payload' => ['name' => 'Giovanni', 'email' => 'giovanni@gmail.com'], 'code' => 422,
                'structure' => ['name', 'email']]
        ];
    }
}

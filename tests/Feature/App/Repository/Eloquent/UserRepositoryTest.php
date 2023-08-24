<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepositoryInterface $repository;

    //Sobrescrevendo o método padrão de setUp do PHPUnit.
    public function setUp(): void
    {
        $this->repository = new UserRepository(new User());

        parent::setUp();
    }

    //Testando se o repositório foi implementado pela interface, seguindo padrões SOLID.
    public function test_implements_interface()
    {
        $this->assertInstanceOf(
            UserRepositoryInterface::class,
            $this->repository
        );
    }

    //Testando a busca por usuários vazios no banco.
    public function test_find_all_empty(): void
    {
        $repository = $this->repository;
        $response = $repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    //Testando a busca de todos os usuários no banco.
    public function test_find_all(): void
    {
        User::factory()->count(10)->create();
        $repository = $this->repository;
        $response = $repository->findAll();

        $this->assertCount(10, $response);
    }

    //Testando a criação de um novo usuário.
    public function test_create()
    {
        $data = [
            'name' => 'Teste',
            'email' => 'teste@gmail.com',
            'password' => bcrypt('1234567')
        ];

        $response = $this->repository->create($data);
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'teste@gmail.com'
        ]);
    }

    //Testando uma exceção na criação de 1 usuário inválido.
    public function test_create_exception()
    {
        $this->expectException(QueryException::class);

        $data = [
            'name' => 'Teste',
            'email' => 'teste@gmail.com'
        ];

        $response = $this->repository->create($data);
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'teste@gmail.com'
        ]);
    }

    //Testando a localização de um usuário no banco por e-mail.
    public function test_find()
    {
        $user = User::factory()->create();
        $response = $this->repository->find($user->email);
        $this->assertNotNull($response);
        $this->assertIsObject($response);
    }

    //Testando a atualização de dados de um usuário.
    public function test_update()
    {
       $user = User::factory()->create();

       $response = $this->repository->update($user->email, [
            'name' => 'Giovanni'
        ]);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'name' => 'Giovanni'
        ]);
    }

    //Testando o delete de um usuário.
    public function test_delete()
    {
        $user = User::factory()->create();

        $response = $this->repository->delete($user->email);

        $this->assertTrue($response);
        $this->assertDatabaseMissing('users', [
            'email' => $user->email
        ]);
    }
}

<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;

class UserTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new User();
    }

    protected function expectedTraits(): array
    {
        return [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class
        ];
    }

    protected function expectedFillables(): array
    {
        return [
            'name',
            'email',
            'password',
        ];
    }

    protected function expectedCasts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function test_incrementing_is_false()
    {
        $incrementing = $this->model()->incrementing;

        $this->assertFalse($incrementing);
    }
}

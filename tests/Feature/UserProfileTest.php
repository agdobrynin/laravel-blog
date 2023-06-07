<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testShowWithRuLocaleForNoAuthUser(): void
    {
        $user = User::factory()->create();

        $this->get('/ru/users/' . $user->id)
            ->assertOk()
            ->assertSeeText('Имя пользователя')
            ->assertSee('value="' . $user->name . '"', false)
            ->assertSeeText('Для комментария авторизуйтесь');
    }

    public function testShowWithRuLocaleForAuthUser(): void
    {
        $user = User::factory(2)->create();

        $this->actingAs($user[0])
            ->get('/ru/users/' . $user[1]->id)
            ->assertOk()
            ->assertSeeText('Имя пользователя')
            ->assertSee('value="' . $user[1]->name . '"', false)
            ->assertDontSee('Для комментария авторизуйтесь')
            ->assertDontSee('Изменить профиль');
    }

    public function testEditFormFailedForNotOwnerProfile(): void
    {
        $user = User::factory(2)->create();

        $this->actingAs($user[0])
            ->get('/ru/users/' . $user[1]->id . '/edit')
            ->assertForbidden();
    }

    public function testUpdateWithRuLocaleForUpdateLocaleEnAndChangeName(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/ru/users/' . $user->id . '/edit')
            ->assertOk()
            ->assertSee('value="' . $user->name . '"', false);

        $this->actingAs($user)
            ->put('/ru/users/' . $user->id, ['name' => 'New Name for test', 'locale' => 'en'])
            ->assertRedirectToRoute('users.show', ['user' => $user->id, 'locale' => 'en']);

        $this->actingAs($user)
            ->get('/en/users/' . $user->id)
            ->assertOk()
            ->assertSeeInOrder([
                'User was updated',
                'Without avatar',
                'User name',
                'New Name for test',
            ]);
    }

    public function testUpdateWithRuLocaleAddAvatar(): void
    {
        $user = User::factory()->create();
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg', 500, 500);

        $this->actingAs($user)
            ->put('/ru/users/' . $user->id, ['avatar' => $file, 'name' => $user->name, 'locale' => 'ru'])
            ->assertRedirectToRoute('users.show', ['user' => $user->id, 'locale' => 'ru']);

        $response = $this->get('/ru/users/' . $user->id)
            ->assertOk()
            ->assertSeeInOrder([
                'Пользователь обновлен',
                'Имя пользователя',
                $user->name,
            ]);

        /** @var Image $image */
        $image = User::find($user->id)->image;
        $url = $image->url();
        $response->assertSee($url . '" alt="Аватар пользователя"', false);
        $this->assertNotNull($image);
    }
}

<?php

namespace Tests\Feature;

use App\User;
use App\Wish;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;
use Tests\AuthorizesUser;
use Tests\TestCase;

class WishControllerTest extends TestCase
{
    use DatabaseMigrations, AuthorizesUser;

    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authUser = factory(User::class)->create();
    }

    public function testIndexRequiresAuthorization()
    {
        $response = $this->get('/api/wishes');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Authorization Token not found']);
    }

    public function testIndex()
    {
        $this->createWishForAuthUser();

        $response = $this->request('GET', '/api/wishes');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'user_id',
                    'title',
                    'goal_amount',
                    'deposited_amount',
                    'description',
                    'due_date',
                    'created_at',
                    'updated_at'
                ],
            ]
        ]);
    }

    public function testCreateRequiresAuthentication()
    {
        $response = $this->post('/api/wishes');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Authorization Token not found']);
    }

    public function testCreate()
    {
        $body = [
            'title' => 'Title',
            'goal_amount' => 1200,
            'description' => 'Description',
            'due_date' => null
        ];

        $response = $this->request('POST', '/api/wishes', $body);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'goal_amount',
                'deposited_amount',
                'description',
                'due_date',
                'created_at',
                'updated_at'
            ]
        ]);
        $this->assertDatabaseHas('wishes', $body);
    }

    public function testUpdateRequiresAuthorization()
    {
        $wishId = Uuid::uuid4();

        $response = $this->patch("/api/wishes/$wishId");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Authorization Token not found']);
    }

    public function testUpdate()
    {
        $wish = $this->createWishForAuthUser();
        $body = [
            'title' => $newTitle = 'New title',
            'description' => $newDescription = 'New description',
            'due_date' => $newDueDate = '2020-12-12'
        ];

        $response = $this->request('PATCH', "/api/wishes/{$wish->id}", $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'goal_amount',
                'deposited_amount',
                'description',
                'due_date',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('wishes', [
            'id' => $wish->id,
            'user_id' => $wish->user_id,
            'title' => $newTitle,
            'goal_amount' => $wish->goal_amount,
            'deposited_amount' => $wish->deposited_amount,
            'description' => $newDescription,
            'due_date' => $newDueDate,
        ]);
    }

    public function testChangeGoalRequiresAuthorization()
    {
        $wishId = Uuid::uuid4();

        $response = $this->patch("/api/wishes/$wishId/goal-amount");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Authorization Token not found']);
    }

    public function testChangeGoal()
    {
        $wish = $this->createWishForAuthUser(['goal_amount' => 10000]);

        $body = ['goal_amount' => $newGoalAmount = 15000];

        $response = $this->request('PATCH', "/api/wishes/{$wish->id}/goal-amount", $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'goal_amount',
                'deposited_amount',
                'description',
                'due_date',
            ]
        ]);
        $this->assertDatabaseHas('wishes', [
            'id' => $wish->id,
            'user_id' => $wish->user_id,
            'title' => $wish->title,
            'goal_amount' => $newGoalAmount,
            'deposited_amount' => $wish->deposited_amount,
            'description' => $wish->description,
            'due_date' => $wish->due_date,
        ]);
    }

    public function testChargeDepositRequiresAuthorization()
    {
        $wishId = Uuid::uuid4();

        $response = $this->patch("/api/wishes/$wishId/deposit");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Authorization Token not found']);
    }

    public function testChargeDeposit()
    {
        $wish = $this->createWishForAuthUser(['deposited_amount' => $deposited = 100]);
        $body = ['deposit_amount' => $newDeposit = 100];

        $response = $this->request('PATCH', "/api/wishes/{$wish->id}/deposit", $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'goal_amount',
                'deposited_amount',
                'description',
                'due_date'
            ]
        ]);
        $this->assertDatabaseHas('wishes', [
            'id' => $wish->id,
            'user_id' => $wish->user_id,
            'title' => $wish->title,
            'goal_amount' => $wish->goal_amount,
            'deposited_amount' => $deposited + $newDeposit,
            'description' => $wish->description,
            'due_date' => $wish->due_date
        ]);
    }

    public function testDeleteRequiresAuthorization()
    {
        $wishId = Uuid::uuid4();

        $response = $this->delete("/api/wishes/$wishId");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Authorization Token not found']);
    }

    public function testDelete()
    {
        $wish = $this->createWishForAuthUser();

        $response = $this->request('DELETE', "/api/wishes/{$wish->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('wishes', $wish->toArray());
    }

    private function createWishForAuthUser(array $attributes = []): Wish
    {
        return $this->createWish(array_merge(['user_id' => $this->authUser], $attributes));
    }

    private function createWish(array $attributes = []): Wish
    {
        return factory(Wish::class)->create($attributes);
    }
}

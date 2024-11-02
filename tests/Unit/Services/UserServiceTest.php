<?php

namespace Tests\Unit\Services;

use App\Services\UserService;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->createTestFile('providers/DataProviderX.json');
        $this->createTestFile('providers/DataProviderY.json');

        $this->userService = app(UserService::class);
    }

    private function createTestFile(string $path): void
    {
        $data = array_map(function ($i) {
            return [
                'parentAmount' => rand(100, 1000),
                'Currency' => ['USD', 'EUR', 'AED'][rand(0, 2)],
                'parentEmail' => "test{$i}@test.com",
                'statusCode' => rand(1, 3),
                'registerationDate' => '2018-11-30',
                'parentIdentification' => "id-{$i}"
            ];
        }, range(1, 1000));

        Storage::put($path, json_encode($data));
    }

    /** @test */
    public function it_filters_by_provider(): void
    {
        $users = $this->userService
            ->getUsers(['provider' => 'DataProviderX'])
            ->take(10)
            ->all();

        foreach ($users as $user) {
            $this->assertEquals('DataProviderX', $user['provider']);
        }
    }

    /** @test */
    public function it_filters_by_status(): void
    {
        $users = $this->userService
            ->getUsers(['status' => 'authorised'])
            ->take(10)
            ->all();

        foreach ($users as $user) {
            $this->assertEquals('authorised', $user['status']);
        }
    }

    /** @test */
    public function it_filters_by_balance_range(): void
    {
        $users = $this->userService
            ->getUsers([
                'balanceMin' => 500,
                'balanceMax' => 600
            ])
            ->take(10)
            ->all();

        foreach ($users as $user) {
            $this->assertGreaterThanOrEqual(500, $user['balance']);
            $this->assertLessThanOrEqual(600, $user['balance']);
        }
    }

    /** @test */
    public function it_handles_multiple_filters(): void
    {
        $users = $this->userService
            ->getUsers([
                'provider' => 'DataProviderX',
                'status' => 'authorised',
                'currency' => 'USD',
                'balanceMin' => 500
            ])
            ->take(10)
            ->all();

        foreach ($users as $user) {
            $this->assertEquals('DataProviderX', $user['provider']);
            $this->assertEquals('authorised', $user['status']);
            $this->assertEquals('USD', strtoupper($user['currency']));
            $this->assertGreaterThanOrEqual(500, $user['balance']);
        }
    }
}

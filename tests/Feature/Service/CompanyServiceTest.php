<?php

namespace Tests\Feature\Service;

use App\Models\User;
use Tests\ServiceTestCase;
use App\Services\UserService;
use App\Actions\RandomGenerator;
use App\Models\Company;
use App\Services\CompanyService;
use Database\Seeders\CompanyTableSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class CompanyServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CompanyService::class);
        $this->userService = app(UserService::class);

        if (User::count() == 0)
            $this->artisan('db:seed', ['--class' => 'UserTableSeeder']);

        if (User::has('companies')->count() == 0) {
            $companyPerUser = 3;
            $companySeeder = new CompanyTableSeeder();
            $companySeeder->callWith(CompanyTableSeeder::class, [$companyPerUser]);    
        }
    }

    public function test_call_save_with_all_field_filled()
    {
        $user = User::has('companies')->get()->first();

        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $default = 0;
        $status = (new RandomGenerator())->generateNumber(0, 1);
        $userId = $user->id;

        $this->service->create(
            $code,
            $name,
            $address,
            $default,
            $status,
            $userId
        );

        $this->assertDatabaseHas('companies', [
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $user = User::has('companies')->get()->first();

        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = null;
        $default = 0;
        $status = (new RandomGenerator())->generateNumber(0, 1);
        $userId = $user->id;

        $this->service->create(
            $code,
            $name,
            $address,
            $default,
            $status,
            $userId
        );

        $this->assertDatabaseHas('companies', [
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $user = User::has('companies')->get()->first();

        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $default = 0;
        $status = (new RandomGenerator())->generateNumber(0, 1);
        $userId = $user->id;

        $company = Company::create([
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'default' => $default,
            'status' => $status,
            'userId' => $userId
        ]);
        $id = $company->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newAddress = $this->faker->address;
        $newDefault = 0;
        $newStatus = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->update(
            id: $id,
            code: $newCode,
            name: $newName,
            address: $newAddress,
            default: $newDefault,
            status: $newStatus
        );

        $this->assertDatabaseHas('companies', [
            'id' => $id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $user = User::has('companies')->get()->first();

        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = null;
        $default = 0;
        $status = (new RandomGenerator())->generateNumber(0, 1);
        $userId = $user->id;

        $company = Company::create([
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'default' => $default,
            'status' => $status,
            'userId' => $userId
        ]);
        $id = $company->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newAddress = null;
        $newDefault = 0;
        $newStatus = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->update(
            id: $id,
            code: $newCode,
            name: $newName,
            address: $newAddress,
            default: $newDefault,
            status: $newStatus
        );

        $this->assertDatabaseHas('companies', [
            'id' => $id,
            'code' => $newCode,
            'name' => $newName,
            'address' => $newAddress,
            'default' => $newDefault,
            'status' => $newStatus
        ]);
    }

    public function test_call_delete()
    {
        $user = User::has('companies')->get()->first();

        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $default = 0;
        $status = (new RandomGenerator())->generateNumber(0, 1);
        $userId = $user->id;

        $company = Company::create([
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'default' => $default,
            'status' => $status,
            'userId' => $userId
        ]);
        $id = $company->id;

        $this->service->delete(
            userId: $userId, 
            id: $id
        );

        $this->assertSoftDeleted('companies', [
            'id' => $id
        ]);
    }

    public function test_call_read_when_user_have_companies_read_with_empty_search()
    {
        $user = User::has('companies')->get()->first();

        $response = $this->service->read(
            userId: $user->id, 
            search: '', 
            paginate: true, 
            page: 1,
            perPage: 10,
            useCache: false
        );

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }

    public function test_call_read_when_user_have_companies_with_special_char_in_search()
    {
        $user = User::has('companies')->get()->first();

        $userId = $user->id;
        $search = " !#$%&'()*+,-./:;<=>?@[\]^_`{|}~";
        $paginate = true;
        $page = 1;
        $perPage = 10;
        $useCache = false;

        $response = $this->service->read(
            userId: $userId, 
            search: $search, 
            paginate: $paginate, 
            page: $page,
            perPage: $perPage,
            useCache: $useCache
        );

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }

    public function test_call_read_when_user_doesnt_have_companies_with_empty_search()
    {
        $user = User::doesnthave('companies')->get();

        if ($user->count() == 0) {
            $email = $this->faker->email;
            $selectedUser = $this->userService->register('testing', $email, 'password', 'on');
        } else {
            $selectedUser = $user->shuffle()->first();
        }

        $response = $this->service->read(
            userId: $selectedUser->id, 
            search: '', 
            paginate: true, 
            page: 1,
            perPage: 10,
            useCache: false
        );

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }

    public function test_call_read_when_user_doesnt_have_companies_with_special_char_in_search()
    {
        $user = User::doesnthave('companies')->get();

        if ($user->count() == 0) {
            $email = $this->faker->email;
            $selectedUser = $this->userService->register('testing', $email, 'password', 'on');
        } else {
            $selectedUser = $user->shuffle()->first();
        }

        $response = $this->service->read(
            userId: $selectedUser->id, 
            search: " !#$%&'()*+,-./:;<=>?@[\]^_`{|}~", 
            paginate: true, 
            page: 1,
            perPage: 10,
            useCache: false
        );

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }
}

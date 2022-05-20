<?php

namespace Tests\Feature\Service;

use App\Models\User;
use App\Services\CompanyService;
use App\Services\UserService;
use Database\Seeders\CompanyTableSeeder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\ServiceTestCase;
use TypeError;

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
        $this->assertTrue(false);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $this->assertTrue(false);
    }

    public function test_call_save_with_existing_code()
    {
        $this->assertTrue(false);
    }

    public function test_call_save_with_null_param()
    {
        $this->assertTrue(false);
    }

    public function test_call_save_with_empty_string_param()
    {
        $this->assertTrue(false);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $this->assertTrue(false);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $this->assertTrue(false);
    }

    public function test_call_edit_with_existing_code()
    {
        $this->assertTrue(false);
    }

    public function test_call_edit_with_null_param()
    {
        $this->assertTrue(false);
    }

    public function test_call_delete()
    {
                $this->assertTrue(false);$this->assertTrue(false);
    }

    public function test_call_delete_nonexistance_id()
    {
        $this->assertTrue(false);
    }

    public function test_call_delete_default_company()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_have_companies_read_with_empty_search()
    {
        $usr = User::has('companies')->get()->first();

        $response = $this->service->read($usr->id, '', true, 10);

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }

    public function test_call_read_when_user_have_companies_with_special_char_in_search()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_have_companies_with_negative_value_in_perpage_param()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_have_companies_without_pagination()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_have_companies_with_null_param()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_doesnt_have_companies_with_empty_search()
    {
        $usr = User::doesnthave('companies')->get();

        if ($usr->count() == 0) {
            $email = $this->faker->email;
            $selectedUsr = $this->userService->register('testing', $email, 'password', 'on');
        } else {
            $selectedUsr = $usr->shuffle()->first();
        }

        $response = $this->service->read($selectedUsr->id, '', true, 10);

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }

    public function test_call_read_when_user_doesnt_have_companies_with_special_char_in_search()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_doesnt_have_companies_with_negative_value_in_perpage_param()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_doesnt_have_companies_without_pagination()
    {
        $this->assertTrue(false);
    }

    public function test_call_read_when_user_doesnt_have_companies_with_null_param()
    {
        $this->assertTrue(false);
    }
}

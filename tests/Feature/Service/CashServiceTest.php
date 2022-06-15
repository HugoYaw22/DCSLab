<?php

namespace Tests\Feature\Service;

use App\Models\Cash;
use App\Models\Company;
use Tests\ServiceTestCase;
use App\Services\CashService;
use App\Actions\RandomGenerator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class CashServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CashService::class);
    }

    public function test_service_call_save()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $is_bank = $this->faker->boolean;
        $status = $this->faker->boolean;

        $this->service->create(
            company_id: $company_id,
            code: $code,
            name: $name,
            is_bank: $is_bank,
            status: $status
        );

        $this->assertDatabaseHas('cashes', [
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_service_call_save_null_field()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $is_bank = $this->faker->boolean;
        $status = $this->faker->boolean;

        $this->service->create(
            company_id: $company_id,
            code: $code,
            name: $name,
            is_bank: $is_bank,
            status: $status
        );

        $this->assertDatabaseHas('cashes', [
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_service_call_edit()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $is_bank = $this->faker->boolean;
        $status = $this->faker->boolean;

        $cash = Cash::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name,
            'is_bank' => $is_bank,
            'status' => $status
        ]);
        $id = $cash->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newIsBank = $this->faker->boolean;
        $newStatus = $this->faker->boolean;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            code: $newCode,
            name: $newName,
            is_bank: $newIsBank,
            status: $newStatus
        );

        $this->assertDatabaseHas('cashes', [
            'id' => $id,
            'company_id' => $company_id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_service_call_edit_null_field()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $is_bank = $this->faker->boolean;
        $status = $this->faker->boolean;

        $cash = Cash::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name,
            'is_bank' => $is_bank,
            'status' => $status
        ]);
        $id = $cash->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newIsBank = $this->faker->boolean;
        $newStatus = $this->faker->boolean;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            code: $newCode,
            name: $newName,
            is_bank: $newIsBank,
            status: $newStatus
        );

        $this->assertDatabaseHas('cashes', [
            'id' => $id,
            'company_id' => $company_id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_service_call_delete()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $is_bank = $this->faker->boolean;
        $status = $this->faker->boolean;

        $cash = Cash::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name,
            'is_bank' => $is_bank,
            'status' => $status
        ]);
        $id = $cash->id;

        $this->service->delete($id);

        $this->assertSoftDeleted('cashes', [
            'id' => $id
        ]);
    }

    public function test_service_call_read_when_user_have_cashes_read_with_empty_search()
    {
        $companyId = Company::inRandomOrder()->first()->id;

        $response = $this->service->read(
            companyId: $companyId, 
            search: '', 
            paginate: true, 
            page: 1,
            perPage: 10,
            useCache: false
        );

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }

    public function test_service_call_read_when_user_have_cashes_with_special_char_in_search()
    {
        $companyId = Company::inRandomOrder()->first()->id;
        $search = " !#$%&'()*+,-./:;<=>?@[\]^_`{|}~";
        $paginate = true;
        $page = 1;
        $perPage = 10;
        $useCache = false;

        $response = $this->service->read(
            companyId: $companyId, 
            search: $search, 
            paginate: $paginate, 
            page: $page,
            perPage: $perPage,
            useCache: $useCache
        );

        $this->assertInstanceOf(Paginator::class, $response);
        $this->assertNotNull($response);
    }
}

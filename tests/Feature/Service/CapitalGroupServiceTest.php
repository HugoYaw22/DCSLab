<?php

namespace Tests\Feature\Service;

use App\Models\CapitalGroup;
use App\Models\Company;
use Tests\ServiceTestCase;
use App\Services\CapitalGroupService;
use App\Actions\RandomGenerator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class CapitalGroupServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CapitalGroupService::class);

        if (CapitalGroup::count() < 2)
            $this->artisan('db:seed', ['--class' => 'CapitalGroupTableSeeder']);
    }

    public function test_call_save_with_all_field_filled()
    {
        $company_id = Company::has('capital_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;

        $this->service->create(
            company_id: $company_id,
            code: $code,
            name: $name
        );

        $this->assertDatabaseHas('capital_groups', [
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $company_id = Company::has('capital_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;

        $this->service->create(
            company_id: $company_id,
            code: $code,
            name: $name
        );

        $this->assertDatabaseHas('capital_groups', [
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $company_id = Company::has('capital_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;

        $capital_group = CapitalGroup::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name
        ]);
        $id = $capital_group->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            code: $newCode,
            name: $newName
        );

        $this->assertDatabaseHas('capital_groups', [
            'id' => $id,
            'company_id' => $company_id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $company_id = Company::has('capital_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;

        $capital_group = CapitalGroup::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name
        ]);
        $id = $capital_group->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            code: $newCode,
            name: $newName
        );

        $this->assertDatabaseHas('capital_groups', [
            'id' => $id,
            'company_id' => $company_id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_call_delete()
    {
        $company_id = Company::has('capital_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;

        $capital_group = CapitalGroup::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name,
        ]);
        $id = $capital_group->id;

        $this->service->delete($id);

        $this->assertSoftDeleted('capital_groups', [
            'id' => $id
        ]);
    }

    public function test_call_read_when_user_have_capital_groups_read_with_empty_search()
    {
        $companyId = Company::has('capital_groups')->inRandomOrder()->first()->id;

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

    public function test_call_read_when_user_have_capital_groups_with_special_char_in_search()
    {
        $companyId = Company::has('capital_groups')->inRandomOrder()->first()->id;
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

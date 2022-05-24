<?php

namespace Tests\Feature\Service;

use App\Models\Warehouse;
use App\Models\Company;
use Tests\ServiceTestCase;
use App\Services\WarehouseService;
use App\Actions\RandomGenerator;
use App\Models\Branch;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class WarehouseServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(WarehouseService::class);

        if (Warehouse::count() < 2)
            $this->artisan('db:seed', ['--class' => 'WarehouseTableSeeder']);
    }

    public function test_call_save_with_all_field_filled()
    {
        $company_id = Company::inRandomOrder()->get()[0]->id;
        $branch_id = Branch::inRandomOrder()->get()[0]->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $city = $this->faker->city;
        $contact = $this->faker->e164PhoneNumber;
        $remarks = null;
        $status = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->create(
            company_id: $company_id,
            branch_id: $branch_id,
            code: $code,
            name: $name,
            address: $address,
            city: $city,
            contact: $contact,
            remarks: $remarks,
            status: $status
        );

        $this->assertDatabaseHas('warehouses', [
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $company_id = Company::inRandomOrder()->get()[0]->id;
        $branch_id = Branch::inRandomOrder()->get()[0]->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = null;
        $city = null;
        $contact = null;
        $remarks = null;
        $status = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->create(
            company_id: $company_id,
            branch_id: $branch_id,
            code: $code,
            name: $name,
            address: $address,
            city: $city,
            contact: $contact,
            remarks: $remarks,
            status: $status
        );

        $this->assertDatabaseHas('warehouses', [
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name
        ]);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $company_id = Company::inRandomOrder()->get()[0]->id;
        $branch_id = Branch::inRandomOrder()->get()[0]->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $city = $this->faker->city;
        $contact = $this->faker->e164PhoneNumber;
        $remarks = null;
        $status = (new RandomGenerator())->generateNumber(0, 1);

        $warehouse = Warehouse::create([
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'contact' => $contact,
            'remarks' => $remarks,
            'status' => $status
        ]);
        $id = $warehouse->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newAddress = $this->faker->address;
        $newCity = $this->faker->city;
        $newContact = $this->faker->e164PhoneNumber;
        $newRemarks = null;
        $newStatus = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->update(
            id: $id,
            company_id: $company_id,
            branch_id: $branch_id,
            code: $newCode,
            name: $newName,
            address: $newAddress,
            city: $newCity,
            contact: $newContact,
            remarks: $newRemarks,
            status: $newStatus
        );

        $this->assertDatabaseHas('warehouses', [
            'id' => $id,
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $company_id = Company::inRandomOrder()->get()[0]->id;
        $branch_id = Branch::inRandomOrder()->get()[0]->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = null;
        $city = null;
        $contact = null;
        $remarks = null;
        $status = (new RandomGenerator())->generateNumber(0, 1);

        $warehouse = Warehouse::create([
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'contact' => $contact,
            'remarks' => $remarks,
            'status' => $status
        ]);
        $id = $warehouse->id;

        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newAddress = null;
        $newCity = null;
        $newContact = null;
        $newRemarks = null;
        $newStatus = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->update(
            id: $id,
            company_id: $company_id,
            branch_id: $branch_id,
            code: $newCode,
            name: $newName,
            address: $newAddress,
            city: $newCity,
            contact: $newContact,
            remarks: $newRemarks,
            status: $newStatus
        );

        $this->assertDatabaseHas('warehouses', [
            'id' => $id,
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $newCode,
            'name' => $newName
        ]);
    }

    public function test_call_delete()
    {
        $company_id = Company::inRandomOrder()->get()[0]->id;
        $branch_id = Branch::inRandomOrder()->get()[0]->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $city = $this->faker->city;
        $contact = $this->faker->e164PhoneNumber;
        $remarks = null;
        $status = (new RandomGenerator())->generateNumber(0, 1);

        $warehouse = Warehouse::create([
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'contact' => $contact,
            'remarks' => $remarks,
            'status' => $status
        ]);
        $id = $warehouse->id;

        $this->service->delete($id);

        $this->assertSoftDeleted('warehouses', [
            'id' => $id
        ]);
    }

    public function test_call_read_when_user_have_companies_read_with_empty_search()
    {
        $companyId = Company::inRandomOrder()->get()[0]->id;

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

    public function test_call_read_when_user_have_companies_with_special_char_in_search()
    {
        $companyId = Company::inRandomOrder()->get()[0]->id;
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

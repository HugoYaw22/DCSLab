<?php

namespace Tests\Feature\Service;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Capital;
use App\Models\Company;
use App\Models\Investor;
use Tests\ServiceTestCase;
use App\Models\CustomerGroup;
use App\Actions\RandomGenerator;
use App\Services\CapitalService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class CapitalServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CapitalService::class);

        if (Capital::count() < 2)
            $this->artisan('db:seed', ['--class' => 'CapitalTableSeeder']);
    }

    public function test_call_save_with_all_field_filled()
    {
        $investor_id = Investor::has('capitals')->inRandomOrder()->first()->id;
        $group_id = CustomerGroup::has('capitals')->inRandomOrder()->first()->id;
        $cash_id = Cash::has('capitals')->inRandomOrder()->first()->id;
        $ref_number = (new RandomGenerator())->generateNumber(1, 9999);
        $date = Carbon::now()->toDateTimeString();
        $capital_status = (new RandomGenerator())->generateNumber(0, 1);
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $this->service->create(
            investor_id: $investor_id,
            group_id: $group_id,
            cash_id: $cash_id,
            ref_number: $ref_number,
            date: $date,
            capital_status: $capital_status,
            amount : $amount,
            remarks: $remarks,
        );

        $this->assertDatabaseHas('capitals', [
            'investor_id' => $investor_id,
            'group_id' => $group_id,
            'cash_id' => $cash_id,
            'ref_number' => $ref_number,
            'date' => $date,
            'capital_status' => $capital_status,
            'amount' => $amount,
            'remarks' => $remarks
        ]);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $investor_id = Investor::has('capitals')->inRandomOrder()->first()->id;
        $group_id = CustomerGroup::has('capitals')->inRandomOrder()->first()->id;
        $cash_id = Cash::has('capitals')->inRandomOrder()->first()->id;
        $ref_number = null;
        $date = Carbon::now()->toDateTimeString();
        $capital_status = (new RandomGenerator())->generateNumber(0, 1);
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $this->service->create(
            investor_id: $investor_id,
            group_id: $group_id,
            cash_id: $cash_id,
            ref_number: $ref_number,
            date: $date,
            capital_status: $capital_status,
            amount : $amount,
            remarks: $remarks,
        );

        $this->assertDatabaseHas('capitals', [
            'investor_id' => $investor_id,
            'group_id' => $group_id,
            'cash_id' => $cash_id,
            'ref_number' => $ref_number,
            'date' => $date,
            'capital_status' => $capital_status,
            'amount' => $amount,
            'remarks' => $remarks
        ]);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $investor_id = Investor::has('capitals')->inRandomOrder()->first()->id;
        $group_id = CustomerGroup::has('capitals')->inRandomOrder()->first()->id;
        $cash_id = Cash::has('capitals')->inRandomOrder()->first()->id;
        $ref_number = (new RandomGenerator())->generateNumber(1, 9999);
        $date = Carbon::now()->toDateTimeString();
        $capital_status = (new RandomGenerator())->generateNumber(0, 1);
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $capital = Capital::create([
            'investor_id' => $investor_id,
            'group_id' => $group_id,
            'cash_id' => $cash_id,
            'ref_number' => $ref_number,
            'date' => $date,
            'capital_status' => $capital_status,
            'amount' => $amount,
            'remarks' => $remarks
        ]);
        $id = $capital->id;

        $newInvestorId = Investor::has('capitals')->inRandomOrder()->first()->id;
        $newGroupId = CustomerGroup::has('capitals')->inRandomOrder()->first()->id;
        $newCashId = Cash::has('capitals')->inRandomOrder()->first()->id;
        $newRefNumber = (new RandomGenerator())->generateNumber(1, 9999);
        $newDate = Carbon::now()->toDateTimeString();
        $newCapitalStatus = (new RandomGenerator())->generateNumber(0, 1);
        $newAmount = $this->faker->randomDigit;
        $newRemarks = null;

        $this->service->update(
            id: $id,
            investor_id: $newInvestorId,
            group_id: $newGroupId,
            cash_id: $newCashId,
            ref_number: $newRefNumber,
            date: $newDate,
            capital_status: $newCapitalStatus,
            amount : $newAmount,
            remarks: $newRemarks
        );

        $this->assertDatabaseHas('capitals', [
            'id' => $id,
            'investor_id' => $newInvestorId,
            'group_id' => $newGroupId,
            'cash_id' => $newCashId,
            'ref_number' => $newRefNumber,
            'date' => $newDate,
            'capital_status' => $newCapitalStatus,
            'amount' => $newAmount,
            'remarks' => $newRemarks
        ]);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $investor_id = Investor::has('capitals')->inRandomOrder()->first()->id;
        $group_id = CustomerGroup::has('capitals')->inRandomOrder()->first()->id;
        $cash_id = Cash::has('capitals')->inRandomOrder()->first()->id;
        $ref_number = null;
        $date = Carbon::now()->toDateTimeString();
        $capital_status = (new RandomGenerator())->generateNumber(0, 1);
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $capital = Capital::create([
            'investor_id' => $investor_id,
            'group_id' => $group_id,
            'cash_id' => $cash_id,
            'ref_number' => $ref_number,
            'date' => $date,
            'capital_status' => $capital_status,
            'amount' => $amount,
            'remarks' => $remarks
        ]);
        $id = $capital->id;

        $newInvestorId = Investor::has('capitals')->inRandomOrder()->first()->id;
        $newGroupId = CustomerGroup::has('capitals')->inRandomOrder()->first()->id;
        $newCashId = Cash::has('capitals')->inRandomOrder()->first()->id;
        $newRefNumber = null;
        $newDate = Carbon::now()->toDateTimeString();
        $newCapitalStatus = (new RandomGenerator())->generateNumber(0, 1);
        $newAmount = $this->faker->randomDigit;
        $newRemarks = null;

        $this->service->update(
            id: $id,
            investor_id: $newInvestorId,
            group_id: $newGroupId,
            cash_id: $newCashId,
            ref_number: $newRefNumber,
            date: $newDate,
            capital_status: $newCapitalStatus,
            amount : $newAmount,
            remarks: $newRemarks
        );

        $this->assertDatabaseHas('capitals', [
            'id' => $id,
            'investor_id' => $newInvestorId,
            'group_id' => $newGroupId,
            'cash_id' => $newCashId,
            'ref_number' => $newRefNumber,
            'date' => $newDate,
            'capital_status' => $newCapitalStatus,
            'amount' => $newAmount,
            'remarks' => $newRemarks
        ]);
    }

    public function test_call_delete()
    {
        $company_id = Company::has('capitals')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $address = $this->faker->address;
        $city = $this->faker->city;
        $contact = $this->faker->e164PhoneNumber;
        $is_main = (new RandomGenerator())->generateNumber(0, 1);
        $remarks = $this->faker->sentence;
        $status = (new RandomGenerator())->generateNumber(0, 1);

        $capital = Capital::create([
            'company_id' => $company_id,
            'code' => $code,
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'contact' => $contact,
            'is_main' => $is_main,
            'remarks' => $remarks,
            'status' => $status
        ]);
        $id = $capital->id;

        $this->service->delete($id);

        $this->assertSoftDeleted('capitals', [
            'id' => $id
        ]);
    }

    public function test_call_read_when_user_have_capitals_read_with_empty_search()
    {
        $companyId = Company::has('capitals')->inRandomOrder()->first()->id;

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

    public function test_call_read_when_user_have_capitals_with_special_char_in_search()
    {
        $companyId = Company::has('capitals')->inRandomOrder()->first()->id;
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

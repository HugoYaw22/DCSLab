<?php

namespace Tests\Feature\Service;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Capital;
use App\Models\Company;
use App\Models\Investor;
use Tests\ServiceTestCase;
use App\Actions\RandomGenerator;
use App\Models\CapitalGroup;
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
    }

    public function test_service_call_save()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $investor_id = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $group_id = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $ref_number = (new RandomGenerator())->generateNumber(1, 9999);
        $date = Carbon::now()->toDateTimeString();
        $capital_status = $this->faker->boolean;
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $this->service->create(
            company_id: $company_id,
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
            'company_id' => $company_id,
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

    public function test_service_call_save_with_null_field()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $investor_id = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $group_id = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $ref_number = null;
        $date = Carbon::now()->toDateTimeString();
        $capital_status = $this->faker->boolean;
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $this->service->create(
            company_id: $company_id,
            investor_id: $investor_id,
            group_id: $group_id,
            cash_id: $cash_id,
            ref_number: $ref_number,
            date: $date,
            capital_status: $capital_status,
            amount: $amount,
            remarks: $remarks,
        );

        $this->assertDatabaseHas('capitals', [
            'company_id' => $company_id,
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

    public function test_service_call_edit()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $investor_id = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $group_id = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $ref_number = (new RandomGenerator())->generateNumber(1, 9999);
        $date = Carbon::now()->toDateTimeString();
        $capital_status = $this->faker->boolean;
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $capital = Capital::create([
            'company_id' => $company_id,
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

        $newInvestorId = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newGroupId = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newCashId = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newRefNumber = (new RandomGenerator())->generateNumber(1, 9999);
        $newDate = Carbon::now()->toDateTimeString();
        $newCapitalStatus = $this->faker->boolean;
        $newAmount = $this->faker->randomDigit;
        $newRemarks = null;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            investor_id: $newInvestorId,
            group_id: $newGroupId,
            cash_id: $newCashId,
            ref_number: $newRefNumber,
            date: $newDate,
            capital_status: $newCapitalStatus,
            amount: $newAmount,
            remarks: $newRemarks
        );

        $this->assertDatabaseHas('capitals', [
            'id' => $id,
            'company_id' => $company_id,
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

    public function test_service_call_edit_with_null_field()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $investor_id = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $group_id = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $ref_number = null;
        $date = Carbon::now()->toDateTimeString();
        $capital_status = $this->faker->boolean;
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $capital = Capital::create([
            'company_id' => $company_id,
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

        $newInvestorId = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newGroupId = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newCashId = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newRefNumber = null;
        $newDate = Carbon::now()->toDateTimeString();
        $newCapitalStatus = $this->faker->boolean;
        $newAmount = $this->faker->randomDigit;
        $newRemarks = null;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            investor_id: $newInvestorId,
            group_id: $newGroupId,
            cash_id: $newCashId,
            ref_number: $newRefNumber,
            date: $newDate,
            capital_status: $newCapitalStatus,
            amount: $newAmount,
            remarks: $newRemarks
        );

        $this->assertDatabaseHas('capitals', [
            'id' => $id,
            'company_id' => $company_id,
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

    public function test_service_call_delete()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $investor_id = Investor::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $group_id = CapitalGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $ref_number = (new RandomGenerator())->generateNumber(1, 9999);
        $date = Carbon::now()->toDateTimeString();
        $capital_status = $this->faker->boolean;
        $amount = $this->faker->randomDigit;
        $remarks = null;

        $capital = Capital::create([
            'company_id' => $company_id,
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

        $this->service->delete($id);

        $this->assertSoftDeleted('capitals', [
            'id' => $id
        ]);
    }

    public function test_service_call_read_when_user_have_capitals_read_with_empty_search()
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

    public function test_service_call_read_when_user_have_capitals_with_special_char_in_search()
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

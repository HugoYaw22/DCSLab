<?php

namespace Tests\Feature\Service;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Expense;
use Tests\ServiceTestCase;
use App\Models\ExpenseGroup;
use App\Actions\RandomGenerator;
use App\Enums\PaymentTermType;
use App\Services\ExpenseService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class ExpenseServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ExpenseService::class);
    }

    public function test_call_save_with_all_field_filled()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $branch_id = Branch::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $expense_group_id = ExpenseGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $date = Carbon::now()->toDateTimeString();
        $payment_term_type = $this->faker->creditCardType;
        $amount = (new RandomGenerator())->generateAlphaNumeric(6);
        $amount_owed = (new RandomGenerator())->generateNumber(5);
        $remarks = $this->faker->sentence;
        $posted = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->create(
            company_id: $company_id,
            branch_id: $branch_id,
            expense_group_id: $expense_group_id,
            cash_id: $cash_id,
            code: $code,
            date: $date,
            payment_term_type: $payment_term_type,
            amount : $amount,
            amount_owed: $amount_owed,
            remarks: $remarks,
            posted: $posted
        );

        $this->assertDatabaseHas('expenses', [
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'expense_group_id' => $expense_group_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'date' => $date,
            'payment_term_type' => $payment_term_type,
            'amount' => $amount,
            'amount_owed' => $amount_owed,
            'remarks' => $remarks,
            'posted' => $posted
        ]);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $branch_id = Branch::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $expense_group_id = ExpenseGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = null;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $date = Carbon::now()->toDateTimeString();
        $payment_term_type = $this->faker->creditCardType;
        $amount = (new RandomGenerator())->generateNumber(5);
        $amount_owed = (new RandomGenerator())->generateNumber(5);
        $remarks = $this->faker->sentence;
        $posted = 0;

        $this->service->create(
            company_id: $company_id,
            branch_id: $branch_id,
            expense_group_id: $expense_group_id,
            cash_id: $cash_id,
            code: $code,
            date: $date,
            payment_term_type: $payment_term_type,
            amount : $amount,
            amount_owed: $amount_owed,
            remarks: $remarks,
            posted: $posted
        );

        $this->assertDatabaseHas('expenses', [
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'expense_group_id' => $expense_group_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'date' => $date,
            'payment_term_type' => $payment_term_type,
            'amount' => $amount,
            'amount_owed' => $amount_owed,
            'remarks' => $remarks,
            'posted' => $posted
        ]);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $branch_id = Branch::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $expense_group_id = ExpenseGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $date = Carbon::now()->toDateTimeString();
        $payment_term_type = $this->faker->creditCardType;
        $amount = (new RandomGenerator())->generateAlphaNumeric(6);
        $amount_owed = (new RandomGenerator())->generateNumber(5);
        $remarks = $this->faker->sentence;
        $posted = (new RandomGenerator())->generateNumber(0, 1);

        $expense = Expense::create([
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'expense_group_id' => $expense_group_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'date' => $date,
            'payment_term_type' => $payment_term_type,
            'amount' => $amount,
            'amount_owed' => $amount_owed,
            'remarks' => $remarks,
            'posted' => $posted
        ]);
        $id = $expense->id;

        $company_id = Company::inRandomOrder()->first()->id;
        $newBranchId = Branch::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newExpenseGroupId = ExpenseGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newCashId = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newDate = Carbon::now()->toDateTimeString();
        $newPaymentTermType = $this->faker->creditCardType;
        $newAmount = (new RandomGenerator())->generateAlphaNumeric(6);
        $newAmountOwed = $this->faker->amount_owed;
        $newRemarks = $this->faker->sentence;
        $newPosted = (new RandomGenerator())->generateNumber(0, 1);

        $this->service->update(
            id: $id,
            company_id: $company_id,
            branch_id: $newBranchId,
            expense_group_id: $newExpenseGroupId,
            cash_id: $newCashId,
            code: $newCode,
            date: $newDate,
            payment_term_type: $newPaymentTermType,
            amount : $newAmount,
            amount_owed: $newAmountOwed,
            remarks: $newRemarks,
            posted: $newPosted
        );

        $this->assertDatabaseHas('expenses', [
            'id' => $id,
            'company_id' => $company_id,
            'branch_id' => $newBranchId,
            'expense_group_id' => $newExpenseGroupId,
            'cash_id' => $newCashId,
            'code' => $newCode,
            'date' => $newDate,
            'payment_term_type' => $newPaymentTermType,
            'amount' => $newAmount,
            'amount_owed' => $newAmountOwed,
            'remarks' => $newRemarks,
            'posted' => $newPosted
        ]);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $branch_id = Branch::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $expense_group_id = ExpenseGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $cash_id = null;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $date = Carbon::now()->toDateTimeString();
        $payment_term_type = $this->faker->creditCardType;
        $amount = (new RandomGenerator())->generateAlphaNumeric(6);
        $amount_owed = (new RandomGenerator())->generateNumber(5);
        $remarks = $this->faker->sentence;
        $posted = null;

        $expense = Expense::create([
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'expense_group_id' => $expense_group_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'date' => $date,
            'payment_term_type' => $payment_term_type,
            'amount' => $amount,
            'amount_owed' => $amount_owed,
            'remarks' => $remarks,
            'posted' => $posted
        ]);
        $id = $expense->id;

        $company_id = Company::inRandomOrder()->first()->id;
        $newBranchId = Branch::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newExpenseGroupId = ExpenseGroup::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        $newCashId = null;
        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newDate = Carbon::now()->toDateTimeString();
        $newPaymentTermType = $this->faker->creditCardType;
        $newAmount = (new RandomGenerator())->generateAlphaNumeric(6);
        $newAmountOwed = $this->faker->amount_owed;
        $newRemarks = $this->faker->sentence;
        $newPosted = null;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            branch_id: $newBranchId,
            expense_group_id: $newExpenseGroupId,
            cash_id: $newCashId,
            code: $newCode,
            date: $newDate,
            payment_term_type: $newPaymentTermType,
            amount : $newAmount,
            amount_owed: $newAmountOwed,
            remarks: $newRemarks,
            posted: $newPosted
        );

        $this->assertDatabaseHas('expenses', [
            'id' => $id,
            'company_id' => $company_id,
            'branch_id' => $newBranchId,
            'expense_group_id' => $newExpenseGroupId,
            'cash_id' => $newCashId,
            'code' => $newCode,
            'date' => $newDate,
            'payment_term_type' => $newPaymentTermType,
            'amount' => $newAmount,
            'amount_owed' => $newAmountOwed,
            'remarks' => $newRemarks,
            'posted' => $newPosted
        ]);
    }

    public function test_call_delete()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $branch_id = Branch::where('company_id', '=', $company_id)->first()->id;
        $expense_group_id = ExpenseGroup::where('company_id', '=', $company_id)->first()->id;
        $cash_id = Cash::where('company_id', '=', $company_id)->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $date = Carbon::now()->toDateTimeString();
        $payment_term_type = $this->faker->creditCardType;
        $amount = (new RandomGenerator())->generateAlphaNumeric(6);
        $amount_owed = (new RandomGenerator())->generateNumber(5);
        $remarks = $this->faker->sentence;
        $posted = (new RandomGenerator())->generateNumber(0, 1);

        $expense = Expense::create([
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            'expense_group_id' => $expense_group_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'date' => $date,
            'payment_term_type' => $payment_term_type,
            'amount' => $amount,
            'amount_owed' => $amount_owed,
            'remarks' => $remarks,
            'posted' => $posted
        ]);
        $id = $expense->id;

        $this->service->delete($id);

        $this->assertSoftDeleted('expenses', [
            'id' => $id
        ]);
    }

    public function test_call_read_when_user_have_expenses_read_with_empty_search()
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

    public function test_call_read_when_user_have_expenses_with_special_char_in_search()
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

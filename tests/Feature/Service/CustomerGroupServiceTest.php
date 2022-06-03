<?php

namespace Tests\Feature\Service;

use App\Models\Cash;
use App\Models\Company;
use Tests\ServiceTestCase;
use App\Models\CustomerGroup;
use App\Actions\RandomGenerator;
use App\Services\CustomerGroupService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Pagination\Paginator;

class CustomerGroupServiceTest extends ServiceTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CustomerGroupService::class);

        if (CustomerGroup::count() < 2)
            $this->artisan('db:seed', ['--class' => 'CustomerGroupTableSeeder']);
    }

    public function test_call_save_with_all_field_filled()
    {
        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_outstanding_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_invoice_age = (new RandomGenerator())->generateAlphaNumeric(4);
        $payment_term = (new RandomGenerator())->generateNumber(1, 365);
        $selling_point = (new RandomGenerator())->generateAlphaNumeric(4);
        $selling_point_multiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $sell_at_cost = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateAlphaNumeric(4);
        $remarks = null;
        $cash_id = Cash::has('customer_groups')->inRandomOrder()->first()->id;

        $this->service->create(
            company_id: $company_id,
            code: $code,
            name: $name,
            max_open_invoice: $max_open_invoice,
            max_outstanding_invoice: $max_outstanding_invoice,
            max_invoice_age: $max_invoice_age,
            payment_term : $payment_term,
            selling_point: $selling_point,
            selling_point_multiple: $selling_point_multiple,
            sell_at_cost: $sell_at_cost,
            price_markup_percent: $price_markup_percent,
            price_markup_nominal: $price_markup_nominal,
            price_markdown_percent : $price_markdown_percent,
            price_markdown_nominal: $price_markdown_nominal,
            round_on: $round_on,
            round_digit: $round_digit,
            remarks: $remarks,
            cash_id: $cash_id
        );

        $this->assertDatabaseHas('customer_groups', [
            'company_id' => $company_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'name' => $name,
            'max_open_invoice' => $max_open_invoice,
            'max_outstanding_invoice' => $max_outstanding_invoice,
            'max_invoice_age' => $max_invoice_age,
            'payment_term' => $payment_term,
            'selling_point' => $selling_point,
            'selling_point_multiple' => $selling_point_multiple,
            'sell_at_cost' => $sell_at_cost,
            'price_markup_percent' => $price_markup_percent,
            'price_markup_nominal' => $price_markup_nominal,
            'price_markdown_percent' => $price_markdown_percent,
            'price_markdown_nominal' => $price_markdown_nominal,
            'round_on' => $round_on,
            'round_digit' => $round_digit,
            'remarks' => $remarks
        ]);
    }

    public function test_call_save_with_minimal_field_filled()
    {
        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = null;
        $max_open_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_outstanding_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_invoice_age = (new RandomGenerator())->generateAlphaNumeric(4);
        $payment_term = (new RandomGenerator())->generateNumber(1, 365);
        $selling_point = (new RandomGenerator())->generateAlphaNumeric(4);
        $selling_point_multiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $sell_at_cost = null;
        $price_markup_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $round_on = null;
        $round_digit = null;
        $remarks = null;
        $cash_id = null;

        $this->service->create(
            company_id: $company_id,
            code: $code,
            name: $name,
            max_open_invoice: $max_open_invoice,
            max_outstanding_invoice: $max_outstanding_invoice,
            max_invoice_age: $max_invoice_age,
            payment_term : $payment_term,
            selling_point: $selling_point,
            selling_point_multiple: $selling_point_multiple,
            sell_at_cost: $sell_at_cost,
            price_markup_percent: $price_markup_percent,
            price_markup_nominal: $price_markup_nominal,
            price_markdown_percent : $price_markdown_percent,
            price_markdown_nominal: $price_markdown_nominal,
            round_on: $round_on,
            round_digit: $round_digit,
            remarks: $remarks,
            cash_id: $cash_id
        );

        $this->assertDatabaseHas('customer_groups', [
            'company_id' => $company_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'name' => $name,
            'max_open_invoice' => $max_open_invoice,
            'max_outstanding_invoice' => $max_outstanding_invoice,
            'max_invoice_age' => $max_invoice_age,
            'payment_term' => $payment_term,
            'selling_point' => $selling_point,
            'selling_point_multiple' => $selling_point_multiple,
            'sell_at_cost' => $sell_at_cost,
            'price_markup_percent' => $price_markup_percent,
            'price_markup_nominal' => $price_markup_nominal,
            'price_markdown_percent' => $price_markdown_percent,
            'price_markdown_nominal' => $price_markdown_nominal,
            'round_on' => $round_on,
            'round_digit' => $round_digit,
            'remarks' => $remarks
        ]);
    }

    public function test_call_edit_with_all_field_filled()
    {
        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_outstanding_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_invoice_age = (new RandomGenerator())->generateAlphaNumeric(4);
        $payment_term = (new RandomGenerator())->generateNumber(1, 365);
        $selling_point = (new RandomGenerator())->generateAlphaNumeric(4);
        $selling_point_multiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $sell_at_cost = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateAlphaNumeric(4);
        $remarks = null;
        $cash_id = Cash::has('customer_groups')->inRandomOrder()->first()->id;

        $customer_group = CustomerGroup::create([
            'company_id' => $company_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'name' => $name,
            'max_open_invoice' => $max_open_invoice,
            'max_outstanding_invoice' => $max_outstanding_invoice,
            'max_invoice_age' => $max_invoice_age,
            'payment_term' => $payment_term,
            'selling_point' => $selling_point,
            'selling_point_multiple' => $selling_point_multiple,
            'sell_at_cost' => $sell_at_cost,
            'price_markup_percent' => $price_markup_percent,
            'price_markup_nominal' => $price_markup_nominal,
            'price_markdown_percent' => $price_markdown_percent,
            'price_markdown_nominal' => $price_markdown_nominal,
            'round_on' => $round_on,
            'round_digit' => $round_digit,
            'remarks' => $remarks
        ]);
        $id = $customer_group->id;

        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newMaxOpenInvoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMaxOutstandingInvoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMaxInvoiceAge = (new RandomGenerator())->generateAlphaNumeric(4);
        $newPaymentTerm = (new RandomGenerator())->generateNumber(1, 365);
        $newSellingPoint = (new RandomGenerator())->generateAlphaNumeric(4);
        $newSellingPointMultiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $newSellAtCost = (new RandomGenerator())->generateAlphaNumeric(4);
        $newPriceMarkupPercent = (new RandomGenerator())->generateAlphaNumeric(4);
        $newPriceMarkupNominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMarkdownPercent = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMarkdownNominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $newRoundOn = (new RandomGenerator())->generateNumber(1, 3);
        $newRoundDigit = (new RandomGenerator())->generateAlphaNumeric(4);
        $newRemarks = null;
        $newCashId = Cash::has('customer_groups')->inRandomOrder()->first()->id;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            code: $newCode,
            name: $newName,
            max_open_invoice: $newMaxOpenInvoice,
            max_outstanding_invoice: $newMaxOutstandingInvoice,
            max_invoice_age: $newMaxInvoiceAge,
            payment_term : $newPaymentTerm,
            selling_point: $newSellingPoint,
            selling_point_multiple: $newSellingPointMultiple,
            sell_at_cost: $newSellAtCost,
            price_markup_percent: $newPriceMarkupPercent,
            price_markup_nominal: $newPriceMarkupNominal,
            price_markdown_percent : $newMarkdownPercent,
            price_markdown_nominal: $newMarkdownNominal,
            round_on: $newRoundOn,
            round_digit: $newRoundDigit,
            remarks: $newRemarks,
            cash_id: $newCashId
        );

        $this->assertDatabaseHas('customer_groups', [
            'id' => $id,
            'company_id' => $company_id,
            'cash_id' => $newCashId,
            'code' => $newCode,
            'name' => $newName,
            'max_open_invoice' => $newMaxOpenInvoice,
            'max_outstanding_invoice' => $newMaxOutstandingInvoice,
            'max_invoice_age' => $newMaxInvoiceAge,
            'payment_term' => $newPaymentTerm,
            'selling_point' => $newSellingPoint,
            'selling_point_multiple' => $newSellingPointMultiple,
            'sell_at_cost' => $newSellAtCost,
            'price_markup_percent' => $newPriceMarkupPercent,
            'price_markup_nominal' => $newPriceMarkupNominal,
            'price_markdown_percent' => $newMarkdownPercent,
            'price_markdown_nominal' => $newMarkdownNominal,
            'round_on' => $newRoundOn,
            'round_digit' => $newRoundDigit,
            'remarks' => $newRemarks
        ]);
    }

    public function test_call_edit_with_minimal_field_filled()
    {
        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = null;
        $max_open_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_outstanding_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_invoice_age = (new RandomGenerator())->generateAlphaNumeric(4);
        $payment_term = (new RandomGenerator())->generateNumber(1, 365);
        $selling_point = (new RandomGenerator())->generateAlphaNumeric(4);
        $selling_point_multiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $sell_at_cost = null;
        $price_markup_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $round_on = null;
        $round_digit = null;
        $remarks = null;
        $cash_id = null;

        $customer_group = CustomerGroup::create([
            'company_id' => $company_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'name' => $name,
            'max_open_invoice' => $max_open_invoice,
            'max_outstanding_invoice' => $max_outstanding_invoice,
            'max_invoice_age' => $max_invoice_age,
            'payment_term' => $payment_term,
            'selling_point' => $selling_point,
            'selling_point_multiple' => $selling_point_multiple,
            'sell_at_cost' => $sell_at_cost,
            'price_markup_percent' => $price_markup_percent,
            'price_markup_nominal' => $price_markup_nominal,
            'price_markdown_percent' => $price_markdown_percent,
            'price_markdown_nominal' => $price_markdown_nominal,
            'round_on' => $round_on,
            'round_digit' => $round_digit,
            'remarks' => $remarks
        ]);
        $id = $customer_group->id;

        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = null;
        $newMaxOpenInvoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMaxOutstandingInvoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMaxInvoiceAge = (new RandomGenerator())->generateAlphaNumeric(4);
        $newPaymentTerm = (new RandomGenerator())->generateNumber(1, 365);
        $newSellingPoint = (new RandomGenerator())->generateAlphaNumeric(4);
        $newSellingPointMultiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $newSellAtCost = null;
        $newPriceMarkupPercent = (new RandomGenerator())->generateAlphaNumeric(4);
        $newPriceMarkupNominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMarkdownPercent = (new RandomGenerator())->generateAlphaNumeric(4);
        $newMarkdownNominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $newRoundOn = null;
        $newRoundDigit = null;
        $newRemarks = null;
        $newCashId = null;

        $this->service->update(
            id: $id,
            company_id: $company_id,
            code: $newCode,
            name: $newName,
            max_open_invoice: $newMaxOpenInvoice,
            max_outstanding_invoice: $newMaxOutstandingInvoice,
            max_invoice_age: $newMaxInvoiceAge,
            payment_term : $newPaymentTerm,
            selling_point: $newSellingPoint,
            selling_point_multiple: $newSellingPointMultiple,
            sell_at_cost: $newSellAtCost,
            price_markup_percent: $newPriceMarkupPercent,
            price_markup_nominal: $newPriceMarkupNominal,
            price_markdown_percent : $newMarkdownPercent,
            price_markdown_nominal: $newMarkdownNominal,
            round_on: $newRoundOn,
            round_digit: $newRoundDigit,
            remarks: $newRemarks,
            cash_id: $newCashId
        );

        $this->assertDatabaseHas('customer_groups', [
            'id' => $id,
            'company_id' => $company_id,
            'cash_id' => $newCashId,
            'code' => $newCode,
            'name' => $newName,
            'max_open_invoice' => $newMaxOpenInvoice,
            'max_outstanding_invoice' => $newMaxOutstandingInvoice,
            'max_invoice_age' => $newMaxInvoiceAge,
            'payment_term' => $newPaymentTerm,
            'selling_point' => $newSellingPoint,
            'selling_point_multiple' => $newSellingPointMultiple,
            'sell_at_cost' => $newSellAtCost,
            'price_markup_percent' => $newPriceMarkupPercent,
            'price_markup_nominal' => $newPriceMarkupNominal,
            'price_markdown_percent' => $newMarkdownPercent,
            'price_markdown_nominal' => $newMarkdownNominal,
            'round_on' => $newRoundOn,
            'round_digit' => $newRoundDigit,
            'remarks' => $newRemarks
        ]);
    }

    public function test_call_delete()
    {
        $company_id = Company::has('customer_groups')->inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_outstanding_invoice = (new RandomGenerator())->generateAlphaNumeric(4);
        $max_invoice_age = (new RandomGenerator())->generateAlphaNumeric(4);
        $payment_term = (new RandomGenerator())->generateNumber(1, 365);
        $selling_point = (new RandomGenerator())->generateAlphaNumeric(4);
        $selling_point_multiple = (new RandomGenerator())->generateAlphaNumeric(4);
        $sell_at_cost = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markup_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_percent = (new RandomGenerator())->generateAlphaNumeric(4);
        $price_markdown_nominal = (new RandomGenerator())->generateAlphaNumeric(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateAlphaNumeric(4);
        $remarks = null;
        $cash_id = Cash::has('customer_groups')->inRandomOrder()->first()->id;
        
        $customer_group = CustomerGroup::create([
            'company_id' => $company_id,
            'cash_id' => $cash_id,
            'code' => $code,
            'name' => $name,
            'max_open_invoice' => $max_open_invoice,
            'max_outstanding_invoice' => $max_outstanding_invoice,
            'max_invoice_age' => $max_invoice_age,
            'payment_term' => $payment_term,
            'selling_point' => $selling_point,
            'selling_point_multiple' => $selling_point_multiple,
            'sell_at_cost' => $sell_at_cost,
            'price_markup_percent' => $price_markup_percent,
            'price_markup_nominal' => $price_markup_nominal,
            'price_markdown_percent' => $price_markdown_percent,
            'price_markdown_nominal' => $price_markdown_nominal,
            'round_on' => $round_on,
            'round_digit' => $round_digit,
            'remarks' => $remarks
        ]);
        $id = $customer_group->id;

        $this->service->delete($id);

        $this->assertSoftDeleted('customer_groups', [
            'id' => $id
        ]);
    }

    public function test_call_read_when_user_have_customer_groups_read_with_empty_search()
    {
        $companyId = Company::has('customer_groups')->inRandomOrder()->first()->id;

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

    public function test_call_read_when_user_have_customer_groups_with_special_char_in_search()
    {
        $companyId = Company::has('customer_groups')->inRandomOrder()->first()->id;
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

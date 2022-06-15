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
    }

    public function test_service_call_save()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateNumber(3);
        $max_outstanding_invoice = (new RandomGenerator())->generateNumber(4);
        $max_invoice_age = (new RandomGenerator())->generateNumber(4);
        $payment_term = (new RandomGenerator())->generateNumber(3);
        $selling_point = (new RandomGenerator())->generateNumber(4);
        $selling_point_multiple = (new RandomGenerator())->generateNumber(4);
        $sell_at_cost = (new RandomGenerator())->generateNumber(4);
        $price_markup_percent = (new RandomGenerator())->generateNumber(4);
        $price_markup_nominal = (new RandomGenerator())->generateNumber(4);
        $price_markdown_percent = (new RandomGenerator())->generateNumber(4);
        $price_markdown_nominal = (new RandomGenerator())->generateNumber(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateNumber(4);
        $remarks = null;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;

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
            'remarks' => $remarks,
            'cash_id' => $cash_id
        ]);
    }

    public function test_service_call_save_with_null_field()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateNumber(5);
        $max_outstanding_invoice = (new RandomGenerator())->generateNumber(5);
        $max_invoice_age = (new RandomGenerator())->generateNumber(5);
        $payment_term = (new RandomGenerator())->generateNumber(5);
        $selling_point = (new RandomGenerator())->generateNumber(4);
        $selling_point_multiple = (new RandomGenerator())->generateNumber(4);
        $sell_at_cost = null;
        $price_markup_percent = (new RandomGenerator())->generateNumber(4);
        $price_markup_nominal = (new RandomGenerator())->generateNumber(4);
        $price_markdown_percent = (new RandomGenerator())->generateNumber(4);
        $price_markdown_nominal = (new RandomGenerator())->generateNumber(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateNumber(4);
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

    public function test_service_call_edit()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateNumber(5);
        $max_outstanding_invoice = (new RandomGenerator())->generateNumber(5);
        $max_invoice_age = (new RandomGenerator())->generateNumber(5);
        $payment_term = (new RandomGenerator())->generateNumber(5);
        $selling_point = (new RandomGenerator())->generateNumber(4);
        $selling_point_multiple = (new RandomGenerator())->generateNumber(4);
        $sell_at_cost = null;
        $price_markup_percent = (new RandomGenerator())->generateNumber(4);
        $price_markup_nominal = (new RandomGenerator())->generateNumber(4);
        $price_markdown_percent = (new RandomGenerator())->generateNumber(4);
        $price_markdown_nominal = (new RandomGenerator())->generateNumber(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateNumber(4);
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

        $company_id = Company::inRandomOrder()->first()->id;
        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newMaxOpenInvoice = (new RandomGenerator())->generateNumber(4);
        $newMaxOutstandingInvoice = (new RandomGenerator())->generateNumber(4);
        $newMaxInvoiceAge = (new RandomGenerator())->generateNumber(4);
        $newPaymentTerm = (new RandomGenerator())->generateNumber(1, 365);
        $newSellingPoint = (new RandomGenerator())->generateNumber(4);
        $newSellingPointMultiple = (new RandomGenerator())->generateNumber(4);
        $newSellAtCost = (new RandomGenerator())->generateNumber(4);
        $newPriceMarkupPercent = (new RandomGenerator())->generateNumber(4);
        $newPriceMarkupNominal = (new RandomGenerator())->generateNumber(4);
        $newMarkdownPercent = (new RandomGenerator())->generateNumber(4);
        $newMarkdownNominal = (new RandomGenerator())->generateNumber(4);
        $newRoundOn = (new RandomGenerator())->generateNumber(1, 3);
        $newRoundDigit = (new RandomGenerator())->generateNumber(4);
        $newRemarks = null;
        $newCashId = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;

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
            'remarks' => $newRemarks,
            'cash_id' => $newCashId
        ]);
    }

    public function test_service_call_edit_with_null_field()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateNumber(5);
        $max_outstanding_invoice = (new RandomGenerator())->generateNumber(5);
        $max_invoice_age = (new RandomGenerator())->generateNumber(5);
        $payment_term = (new RandomGenerator())->generateNumber(5);
        $selling_point = (new RandomGenerator())->generateNumber(4);
        $selling_point_multiple = (new RandomGenerator())->generateNumber(4);
        $sell_at_cost = null;
        $price_markup_percent = (new RandomGenerator())->generateNumber(4);
        $price_markup_nominal = (new RandomGenerator())->generateNumber(4);
        $price_markdown_percent = (new RandomGenerator())->generateNumber(4);
        $price_markdown_nominal = (new RandomGenerator())->generateNumber(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateNumber(4);
        $remarks = null;
        $cash_id = null;

        $customer_group = CustomerGroup::create([
            'company_id' => $company_id,
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
            'remarks' => $remarks,
            'cash_id' => $cash_id
        ]);
        $id = $customer_group->id;

        $company_id = Company::inRandomOrder()->first()->id;
        $newCode = (new RandomGenerator())->generateAlphaNumeric(5);
        $newName = $this->faker->name;
        $newMaxOpenInvoice = (new RandomGenerator())->generateNumber(5);
        $newMaxOutstandingInvoice = (new RandomGenerator())->generateNumber(5);
        $newMaxInvoiceAge = (new RandomGenerator())->generateNumber(5);
        $newPaymentTerm = (new RandomGenerator())->generateNumber(5);
        $newSellingPoint = (new RandomGenerator())->generateNumber(4);
        $newSellingPointMultiple = (new RandomGenerator())->generateNumber(5);
        $newSellAtCost = null;
        $newPriceMarkupPercent = (new RandomGenerator())->generateNumber(5);
        $newPriceMarkupNominal = (new RandomGenerator())->generateNumber(5);
        $newMarkdownPercent = (new RandomGenerator())->generateNumber(4);
        $newMarkdownNominal = (new RandomGenerator())->generateNumber(4);
        $newRoundOn = (new RandomGenerator())->generateNumber(1, 3);
        $newRoundDigit = (new RandomGenerator())->generateNumber(4);
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
            'remarks' => $newRemarks,
            'cash_id' => $newCashId
        ]);
    }

    public function test_service_call_delete()
    {
        $company_id = Company::inRandomOrder()->first()->id;
        $code = (new RandomGenerator())->generateAlphaNumeric(5);
        $name = $this->faker->name;
        $max_open_invoice = (new RandomGenerator())->generateNumber(4);
        $max_outstanding_invoice = (new RandomGenerator())->generateNumber(4);
        $max_invoice_age = (new RandomGenerator())->generateNumber(4);
        $payment_term = (new RandomGenerator())->generateNumber(1, 365);
        $selling_point = (new RandomGenerator())->generateNumber(4);
        $selling_point_multiple = (new RandomGenerator())->generateNumber(4);
        $sell_at_cost = (new RandomGenerator())->generateNumber(4);
        $price_markup_percent = (new RandomGenerator())->generateNumber(4);
        $price_markup_nominal = (new RandomGenerator())->generateNumber(4);
        $price_markdown_percent = (new RandomGenerator())->generateNumber(4);
        $price_markdown_nominal = (new RandomGenerator())->generateNumber(4);
        $round_on = (new RandomGenerator())->generateNumber(1, 3);
        $round_digit = (new RandomGenerator())->generateNumber(4);
        $remarks = null;
        $cash_id = Cash::where('company_id', '=', $company_id)->inRandomOrder()->first()->id;
        
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

    public function test_service_call_read_when_user_have_customer_groups_read_with_empty_search()
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

    public function test_service_call_read_when_user_have_customer_groups_with_special_char_in_search()
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

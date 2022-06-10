<?php

namespace App\Http\Requests;

use App\Rules\uniqueCode;
use App\Enums\ActiveStatus;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $companyId = $this->has('company_id') ? Hashids::decode($this['company_id'])[0]:null;

        $nullableArr = [
            'sell_at_cost' => 'nullable',
            'round_on' => 'nullable',
            'remarks' => 'nullable',
        ];

        $currentRouteMethod = $this->route()->getActionMethod();
        switch($currentRouteMethod) {
            case 'store':
                $rules_store = [
                    'code' => ['required', 'max:255'],
                    'name' => 'required|min:3|max:255',
                    'max_open_invoice' => 'required|integer|digits_between:1,11',
                    'max_outstanding_invoice' => 'required|integer|digits_between:1,16',
                    'max_invoice_age' => 'required|integer|digits_between:1,11',
                    'payment_term' => 'required|integer|digits_between:1,11',
                    'selling_point' => 'required|integer|digits_between:1,8',
                    'selling_point_multiple' => 'required|integer|digits_between:1,16',
                    'price_markup_percent' => 'required|integer|digits_between:1,16',
                    'price_markup_nominal' => 'required|integer|digits_between:1,16',
                    'price_markdown_percent' => 'required|integer|digits_between:1,16',
                    'price_markdown_nominal' => 'required|integer|digits_between:1,16',
                    'round_digit' => 'required|integer|digits_between:1,11',
                    'cash_id' => ['required', 'bail'],
                ];
                return array_merge($rules_store, $nullableArr);
            case 'update':
                $rules_update = [         
                    'code' => ['required', 'max:255'],
                    'name' => 'required|min:3|max:255',
                    'max_open_invoice' => 'required|integer|digits_between:1,11',
                    'max_outstanding_invoice' => 'required|integer|digits_between:1,16',
                    'max_invoice_age' => 'required|integer|digits_between:1,11',
                    'payment_term' => 'required|integer|digits_between:1,11',
                    'selling_point' => 'required|integer|digits_between:1,8',
                    'selling_point_multiple' => 'required|integer|digits_between:1,16',
                    'price_markup_percent' => 'required|integer|digits_between:1,16',
                    'price_markup_nominal' => 'required|integer|digits_between:1,16',
                    'price_markdown_percent' => 'required|integer|digits_between:1,16',
                    'price_markdown_nominal' => 'required|integer|digits_between:1,16',
                    'round_digit' => 'required|integer|digits_between:1,11',
                    'cash_id' => ['required', 'bail'],
                ];
                return array_merge($rules_update, $nullableArr);
            default:
                return [
                    '' => 'required'
                ];
        }
    }

    public function attributes()
    {
        return [
            'company_id' => trans('validation_attributes.company'),
        ];
    }

    public function validationData()
    {
        $additionalArray = [];

        return array_merge($this->all(), $additionalArray);
    }

    public function prepareForValidation()
    {
        $currentRouteMethod = $this->route()->getActionMethod();
        switch($currentRouteMethod) {
            case 'read':
                $this->merge([
                    'company_id' => $this->has('companyId') ? Hashids::decode($this['companyId'])[0] : '',
                    'paginate' => $this->has('paginate') ? filter_var($this->paginate, FILTER_VALIDATE_BOOLEAN) : true,
                ]);
                break;
            case 'store':
                $this->merge([
                    'select_at_cost' => $this->has('select_at_cost') ? $this['select_at_cost'] : null,
                    'round_on' => $this->has('round_on') ? $this['round_on'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                    'cash_id' => $this->has('cash_id') ? Hashids::decode($this['cash_id'])[0] : '',
                ]);
            case 'update':
                $this->merge([
                    'company_id' => $this->has('company_id') ? Hashids::decode($this['company_id'])[0] : '',
                    'select_at_cost' => $this->has('select_at_cost') ? $this['select_at_cost'] : null,
                    'round_on' => $this->has('round_on') ? $this['round_on'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                    'cash_id' => $this->has('cash_id') ? Hashids::decode($this['cash_id'])[0] : '',
                ]);
                break;
            default:
                $this->merge([]);
        }
    }
}

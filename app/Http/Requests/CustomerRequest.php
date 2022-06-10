<?php

namespace App\Http\Requests;

use App\Rules\uniqueCode;
use App\Enums\ActiveStatus;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'address' => 'nullable',
            'city' => 'nullable',
            'contact' => 'nullable',
            'remarks' => 'nullable',
            'is_member' => 'nullable',
            'zone' => 'nullable',
            'tax_id' => 'nullable',
        ];

        $currentRouteMethod = $this->route()->getActionMethod();
        switch($currentRouteMethod) {
            case 'store':
                $rules_store = [
                    'customer_group_id' => ['required', 'bail'],
                    'code' => ['required', 'max:255'],
                    'name' => 'required|min:3|max:255',
                    'max_open_invoice' => 'required|integer|digits_between:1,11',
                    'max_outstanding_invoice' => 'required|numeric|min:0|max:999999999999999',
                    'max_invoice_age' => 'required|integer|digits_between:1,11',
                    'payment_term' => 'required|integer|digits_between:1,11',
                    'status' => [new Enum(ActiveStatus::class)]
                ];
                return array_merge($rules_store, $nullableArr);
            case 'update':
                $rules_update = [
                    'customer_group_id' => ['required', 'bail'],
                    'code' => ['required', 'max:255'],
                    'name' => 'required|min:3|max:255',
                    'max_open_invoice' => 'required|integer|digits_between:1,11',
                    'max_outstanding_invoice' => 'required|numeric|min:0|max:999999999999999',
                    'max_invoice_age' => 'required|integer|digits_between:1,11',
                    'payment_term' => 'required|integer|digits_between:1,11',
                    'status' => [new Enum(ActiveStatus::class)]
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
                    'customer_group_id' => $this->has('customer_group_id') ? Hashids::decode($this['customer_group_id'])[0] : '',
                    'address' => $this->has('address') ? $this['address'] : null,
                    'city' => $this->has('city') ? $this['city'] : null,
                    'contact' => $this->has('contact') ? $this['contact'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                    'is_member' => $this->has('is_member') ? $this['is_member'] : null,
                    'zone' => $this->has('zone') ? $this['zone'] : null,
                    'tax_id' => $this->has('tax_id') ? $this['tax_id'] : null,
                ]);
            case 'update':
                $this->merge([
                    'company_id' => $this->has('company_id') ? Hashids::decode($this['company_id'])[0] : '',
                    'customer_group_id' => $this->has('customer_group_id') ? Hashids::decode($this['customer_group_id'])[0] : '',
                    'address' => $this->has('address') ? $this['address'] : null,
                    'city' => $this->has('city') ? $this['city'] : null,
                    'contact' => $this->has('contact') ? $this['contact'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                    'is_member' => $this->has('is_member') ? $this['is_member'] : null,
                    'zone' => $this->has('zone') ? $this['zone'] : null,
                    'tax_id' => $this->has('tax_id') ? $this['tax_id'] : null,
                ]);
                break;
            default:
                $this->merge([]);
        }
    }
}

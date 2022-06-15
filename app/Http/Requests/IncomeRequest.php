<?php

namespace App\Http\Requests;

use App\Rules\uniqueCode;
use App\Enums\ActiveStatus;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
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
            'remarks' => 'nullable',
            'posted' => 'nullable',
        ];

        $currentRouteMethod = $this->route()->getActionMethod();
        switch($currentRouteMethod) {
            case 'store':
                $rules_store = [
                    'company_id' => ['required', 'bail'],
                    'branch_id' => ['required', 'bail'],
                    'income_group_id' => ['required', 'bail'],
                    'cash_id' => ['required', 'bail'],
                    'code' => ['required', 'max:255'],
                    'payment_term_type' => 'required|integer|digits_between:1,11',
                    'amount' => 'required|integer|digits_between:1,19',
                    'amount_owed' => 'required|integer|digits_between:1,19',
                ];
                return array_merge($rules_store, $nullableArr);
            case 'update':
                $rules_update = [
                    'company_id' => ['required', 'bail'],
                    'branch_id' => ['required', 'bail'],
                    'income_group_id' => ['required', 'bail'],
                    'cash_id' => ['required', 'bail'],
                    'code' => ['required', 'max:255'],
                    'payment_term_type' => 'required|integer|digits_between:1,11',
                    'amount' => 'required|integer|digits_between:1,19',
                    'amount_owed' => 'required|integer|digits_between:1,19',
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
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                    'posted' => $this->has('posted') ? $this['posted'] : null,
                    'income_group_id' => $this->has('income_group_id') ? Hashids::decode($this['income_group_id'])[0] : '',
                ]);
            case 'update':
                $this->merge([
                    'company_id' => $this->has('company_id') ? Hashids::decode($this['company_id'])[0] : '',
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                    'posted' => $this->has('posted') ? $this['posted'] : null,
                    'income_group_id' => $this->has('income_group_id') ? Hashids::decode($this['income_group_id'])[0] : '',
                ]);
                break;
            default:
                $this->merge([]);
        }
    }
}

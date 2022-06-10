<?php

namespace App\Http\Requests;

use App\Enums\ActiveStatus;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CapitalRequest extends FormRequest
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
        ];

        $currentRouteMethod = $this->route()->getActionMethod();
        switch($currentRouteMethod) {
            case 'store':
                $rules_store = [
                    'company_id' => ['required', 'bail'],
                    'ref_number' => 'required|integer|digits_between:1,255',
                    'group_id' => 'required',
                    'capital_status' => 'required',
                    'amount' => 'required|integer|digits_between:1,29',
                ];
                return array_merge($rules_store, $nullableArr);
            case 'update':
                $rules_update = [
                    'company_id' => ['required', 'bail'],
                    'ref_number' => 'required|integer|digits_between:1,255',
                    'group_id' => 'required',
                    'capital_status' => 'required',
                    'amount' => 'required|integer|digits_between:1,29',
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
                    'group_id' => $this->has('group_id') ? Hashids::decode($this['group_id'])[0] : '',
                    'address' => $this->has('address') ? $this['address'] : null,
                    'city' => $this->has('city') ? $this['city'] : null,
                    'contact' => $this->has('contact') ? $this['contact'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                ]);
            case 'update':
                $this->merge([
                    'company_id' => $this->has('company_id') ? Hashids::decode($this['company_id'])[0] : '',
                    'group_id' => $this->has('group_id') ? Hashids::decode($this['group_id'])[0] : '',
                    'address' => $this->has('address') ? $this['address'] : null,
                    'city' => $this->has('city') ? $this['city'] : null,
                    'contact' => $this->has('contact') ? $this['contact'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                ]);
                break;
            default:
                $this->merge([]);
        }
    }
}

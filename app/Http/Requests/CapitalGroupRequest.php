<?php

namespace App\Http\Requests;

use App\Enums\ActiveStatus;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CapitalGroupRequest extends FormRequest
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

        $nullableArr = [
            'date' => 'nullable',
            'remarks' => 'nullable',
        ];

        $currentRouteMethod = $this->route()->getActionMethod();
        switch($currentRouteMethod) {
            case 'store':
                $rules_store = [
                    'investor_id' => ['required'],
                    'group_id' => ['required'],
                    'cash_id' => ['required'],
                    'ref_number' => 'required|integer|digits_between:1,255',
                    'capital_status' => 'required',
                    'amount' => 'required|integer|digits_between:1,19',
                ];
                return array_merge($rules_store, $nullableArr);
            case 'update':
                $rules_update = [
                    'company_id' => ['required', 'bail'],
                    'ref_number' => 'required|integer|digits_between:1,255',
                    'group_id' => 'required',
                    'capital_status' => 'required',
                    'amount' => 'required|integer|digits_between:1,19',
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
                    'investor_id' => $this->has('investor_id') ? Hashids::decode($this['investor_id'])[0] : '',
                    'group_id' => $this->has('group_id') ? Hashids::decode($this['group_id'])[0] : '',
                    'cash_id' => $this->has('cash_id') ? Hashids::decode($this['cash_id'])[0] : '',
                    'date' => $this->has('date') ? $this['date'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,
                ]);
            case 'update':
                $this->merge([
                    'company_id' => $this->has('company_id') ? Hashids::decode($this['company_id'])[0] : '',
                    'investor_id' => $this->has('investor_id') ? Hashids::decode($this['investor_id'])[0] : '',
                    'group_id' => $this->has('group_id') ? Hashids::decode($this['group_id'])[0] : '',
                    'cash_id' => $this->has('cash_id') ? Hashids::decode($this['cash_id'])[0] : '',
                    'date' => $this->has('date') ? $this['date'] : null,
                    'remarks' => $this->has('remarks') ? $this['remarks'] : null,

                ]);
                break;
            default:
                $this->merge([]);
        }
    }
}

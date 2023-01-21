<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'tin' => 'required|unique:taxpayers,tin',
            'tax_year' => 'required',
            'demand_date' => 'required',
            'corresponding_section' => 'required',
            'demand_amount' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        // $this->merge([
        //     'is_active' => $this->boolean('is_active'),
        // ]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferFundRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'credit_wallet_id' => 'required|integer',
            'debit_wallet_id' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'credit_wallet_id.required' => "Credit wallet is required"
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderMotorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'superadmin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'orders' => 'required|array',
            'orders.*.stock_id' => 'required|exists:stocks,id',
            'orders.*.jumlah_motor' => 'required|integer|min:1',
            'pengiriman' => 'required|string',
            'pembayaran' => 'required|string',
            'nama_pembeli' => 'required|string|max:255',
            'alamat_pembeli' => 'required|string',
            'no_hp_pembeli' => 'required|string|max:20',
        ];
    }
}

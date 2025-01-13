<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Kredit',
            'metode_pengiriman' => 'required|in:Kapal Kargo,Truck,Pick-Up,Mobil-Box',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_pengiriman' => 'nullable|date',
            'tanggal_penerimaan' => 'nullable|date',
            'status' => 'nullable|in:Pending,Completed,Cancelled',
            'invoice_pembelian' => 'nullable|unique:pembelians,invoice_pembelian'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Vendor harus dipilih',
            'vendor_id.exists' => 'Vendor tidak valid',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
            'metode_pengiriman.required' => 'Metode pengiriman harus dipilih',
            'metode_pengiriman.in' => 'Metode pengiriman tidak valid',
            'tanggal_pembelian.date' => 'Format tanggal pembelian tidak valid',
            'tanggal_pengiriman.date' => 'Format tanggal pengiriman tidak valid',
            'tanggal_penerimaan.date' => 'Format tanggal penerimaan tidak valid',
            'status.in' => 'Status tidak valid'
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'description' => $this->description,
            'paymentDue' => $this->paymentDue,
            'paymentTerms' => $this->paymentTerms,
            'clientName' => $this->clientName,
            'clientEmail' => $this->clientEmail,
            'status' => $this->status,
            'senderAddress' => $this->senderAddress(),
            'clientAddress' => $this->clientAddress(),
            'items' => $this->items(),
            'total' => $this->total
        ];
    }

    private function senderAddress() {
        $invoice_details = $this->invoice_details;
        return [
            'street' => $invoice_details->sender_street,
            'city' => $invoice_details->sender_city,
            'postCode' => $invoice_details->sender_postCode,
            'country' => $invoice_details->sender_country,
        ];
    }

    private function clientAddress() {
        $invoice_details = $this->invoice_details;
        return [
            'street' => $invoice_details->client_street,
            'city' => $invoice_details->client_city,
            'postCode' => $invoice_details->client_postCode,
            'country' => $invoice_details->client_country,
        ];
    }

    private function items() {
        return $this->items;
    }
}

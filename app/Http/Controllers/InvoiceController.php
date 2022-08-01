<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InvoiceResource::collection(Invoice::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $this->getId();
        $total = 0;

        if(count($request->items) > 0) {
            $items = [];
            foreach($request->items as $index => $item) {
                $items[$index] = [
                    'invoice_id' => $id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ];

                $total += $item['total'];
            }
            Item::insert($items);
        }

        $request->merge(['id' => $id, 'invoice_id' => $id, 'total' => $total]);

        $invoice = new Invoice();
        $invoice->create($request->all());
        // $invoice->create($request->except('_token'));

        $invoice_details = new InvoiceDetail();
        $invoice_details->create($request->all());

        return new InvoiceResource($invoice);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new InvoiceResource(Invoice::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function getId() {
        genLetters:
        $letters = Str::upper(Str::random(2));

        if (preg_match('~[0-9]+~', $letters)) {
            goto genLetters;
        }

        $numbers = rand(1111,9999);
        $id = $letters.$numbers;

        if(Invoice::where('id', $id)->exists()) {
            goto genLetters;
        }

        return $id;
    }
}

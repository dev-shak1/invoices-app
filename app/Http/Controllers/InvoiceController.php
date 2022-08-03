<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceItem;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        if($request->save == true) {
            $validator = Validator::make($request->all(), [
                'clientName' => 'required|string',
                'clientEmail' => 'required|email',
                'description' => 'required',
                'paymentTerms' => 'required|integer',
                'sender_street' => 'required',
                'sender_city' => 'required',
                'sender_postCode' => 'required',
                'sender_country' => 'required',
                'client_street' => 'required',
                'client_city' => 'required',
                'client_postCode' => 'required',
                'client_country' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Failed',
                    'errors' => $validator->errors()
                ]);
            }

            if(count($request->items) == 0) {
                return response()->json([
                    'message' => 'Validation Failed',
                    'errors' => 'Invoice should contain atleast 1 item!'
                ]);
            }

            if($request->paymentTerms > 0) {
                $paymentDue = Carbon::now()->addDays($request->paymentTerms);
            }

            $request->merge(['status' => 'pending', 'paymentDue' => $paymentDue]);
        }
        else {
            $request->merge(['status' => 'draft']);
        }

        $id = $this->getId();
        $total = 0;

        if(count($request->items) > 0) {
            $items = [];
            foreach($request->items as $index => $item) {
                $item_total = $item['quantity'] * $item['price'];

                $items[$index] = [
                    'invoice_id' => $id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'total' => $item_total
                ];

                $total += $item_total;
            }
        }

        $request->merge(['id' => $id, 'invoice_id' => $id, 'total' => $total]);

        $invoice = new Invoice();
        $invoice->create($request->all());
        // $invoice->create($request->except('_token'));

        $invoice_details = new InvoiceDetail();
        $invoice_details->create($request->all());

        InvoiceItem::insert($items);

        return new InvoiceResource(Invoice::findOrFail($id));
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
        $validator = Validator::make($request->all(), [
            'clientName' => 'required|string',
            'clientEmail' => 'required|email',
            'description' => 'required',
            'paymentTerms' => 'required|integer',
            'sender_street' => 'required',
            'sender_city' => 'required',
            'sender_postCode' => 'required',
            'sender_country' => 'required',
            'client_street' => 'required',
            'client_city' => 'required',
            'client_postCode' => 'required',
            'client_country' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ]);
        }

        if(count($request->items) == 0) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => 'Invoice should contain atleast 1 item!'
            ]);
        }

        if($request->status == 'draft') {
            $request->merge(['status' => 'pending']);
        }

        $invoice = Invoice::find($id);

        $invoice_details = InvoiceDetail::where('invoice_id', $id)->first();
        $invoice_details->update($request->all());

        $total = 0;
        if(count($request->items) > 0) {
            $db_items_arr = $invoice->invoice_items()->pluck('item_id')->toArray();
            $req_items_arr = collect($request->items)->pluck('item_id')->toArray();

            $del_items = array_diff($db_items_arr,$req_items_arr);
            // remove items that removed by user
            $invoice->invoice_items()->whereIn('item_id', $del_items)->delete();

            $items = [];
            foreach($request->items as $index => $item) {
                $item_total = $item['quantity'] * $item['price'];

                if(!in_array($item['item_id'], $db_items_arr)) {
                    $items[$index] = [
                        'invoice_id' => $id,
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                        'total' => $item_total
                    ];
                }

                $total += $item_total;
            }
        }

        if($request->paymentTerms > 0) {
            $paymentDue = Carbon::parse($invoice->created_at)->addDays($request->paymentTerms);
        }

        $request->merge(['status' => 'pending', 'paymentDue' => $paymentDue, 'total' => $total]);
        $invoice->update($request->all());

        InvoiceItem::insert($items);

        return new InvoiceResource(Invoice::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Invoice deleted successfully!'
        ]);
    }

    public function updateStatus($id) {
        Invoice::find($id)->update([
            'status' => 'paid'
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Status Updated successfully!'
        ]);
    }

    protected function getId() {
        GEN_STRING:
        $string = Str::upper(Str::random(2));

        if (preg_match('~[0-9]+~', $string)) {
            goto GEN_STRING;
        }

        $numbers = rand(1111,9999);
        $id = $string.$numbers;

        if(Invoice::where('id', $id)->exists()) {
            goto GEN_STRING;
        }

        return $id;
    }
}

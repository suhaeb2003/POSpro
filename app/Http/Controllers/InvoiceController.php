<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function createSeal(Request $request)
    {
        $user_id = $request->header('id');
        $products = Product::where('user_id', $user_id)->get();
        $customers = Customer::where('user_id', $user_id)->get();
        return Inertia::render('CreateSeal/CreateSeal', ['products' => $products, 'customers' => $customers]);
    }
    function invoicePage(Request $request)
    {
        $user_id = $request->header('id');
        $invoice = Invoice::where('user_id', $user_id)->with('customer')->get();
        return Inertia::render('Invoices/InvoicePage')->with('invoices', $invoice);
    }
    function invoiceList(Request $request)
    {
        $user_id = $request->header('id');
        $invoice = Invoice::where('user_id', $user_id)->with('customer')->get();
        if ($invoice->isEmpty()) {
            return response()->json([
                'message' => 'Your don\'t have any invoice'
            ]);
        } else {
            return $invoice;
        }
    }
    function createInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');
            $customer_id = $request->input('customer_id');
            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id
            ]);
            $invoiceId = $invoice->id;
            $products = $request->input('product');
            foreach ($products as $singleProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceId,
                    'user_id' => $user_id,
                    'product_id' => $singleProduct['id'],
                    'qty' => $singleProduct['unit'],
                    'sale_price' => $singleProduct['productPrice']
                ]);
            }
            DB::commit();
            $data = [
                'message' => 'Invoice Created Successfully',
                'status' => true
            ];
            return redirect()->route('create-seal')->with($data);
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                'message' => 'Invoice Created Failed',
                'status' => false
            ];
            return redirect()->route('create-seal')->with($data);
        }
    }
    function invoiceDetials(Request $request)
    {
        $user_id = $request->header('id');
        $customerDetials = Customer::where('user_id', $user_id)->where('id', $request->input('customer_id'))->first();
        $invoiceTotal = Invoice::where('user_id', $user_id)->where('id', $request->input('invoice_id'))->first();
        $invoiceProduct = InvoiceProduct::where('user_id', $user_id)->where('invoice_id', $request->input('invoice_id'))->get();
        return array(
            'customer' => $customerDetials,
            'invoice' => $invoiceTotal,
            'Porduct' => $invoiceProduct
        );
    }
    function deleteInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $invoice_id = $request->id;
            InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id', $user_id)->delete();
            Invoice::where('id', $invoice_id)->where('user_id', $user_id)->delete();
            DB::commit();
            $data = [
                'message' => 'Delete Successfully',
                'status' => true
            ];
            return redirect()->route('invoice')->with($data);
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                'message' => 'Delete Failed',
                'status' => false
            ];
            return redirect()->route('invoice')->with($data);
        }
    }
}

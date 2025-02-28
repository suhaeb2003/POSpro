<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function customerPage(Request $request)
    {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return Inertia::render('Customer/CustomerPage')->with('customers', $customers);
    }
    function customerAdd(Request $request)
    {   $user_id = $request->header('id');
        $id=$request->query('id');
        $customer = Customer::where('id', $id)->where('user_id', $user_id)->first();
        return Inertia::render('Customer/CustomerAdd')->with('customer', $customer);
    }

    function customerCreate(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required'
        ]);
        try {
            Customer::where('user_id', $user_id)->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'user_id' => $user_id
            ]);
            $data = [
                'message' => 'Customer Create Successfull',
                'status' => true,
            ];
            return redirect()->route('customer-add')->with($data);
        } catch (Exception $e) {
            $data = [
                'message' => 'Customer Create Failed',
                'status' => false,
            ];
            return redirect()->route('customer-add')->with($data);
        }
    }
    function customerUpdate(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required'
        ]);
        try {
            Customer::where('id', $id)->where('user_id', $user_id)->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile']
            ]);
            $data = [
                'message' => 'Customer Update Successfull',
                'status' => true,
            ];
            return redirect()->route('customer-add')->with($data);
        } catch (Exception $e) {
            $data = [
                'message' => 'Customer Update Failed',
                'status' => false,
            ];
            return redirect()->route('customer-add')->with($data);
        }
    }
    function customerDelete(Request $request)
    {
        try {
            $user_id = $request->header('id');

            $id = $request->id;
            Customer::where('id', $id)->where('user_id', $user_id)->delete();
            $data = [
                'message' => 'Customer Delete Successfull',
                'status' => true,
            ];
            return redirect()->route('customer')->with($data);
        } catch (Exception $e) {
            $data = [
                'message' => 'Customer Delete Failed',
                'status' => false,
            ];
            return redirect()->route('customer')->with($data);
        }
    }
}

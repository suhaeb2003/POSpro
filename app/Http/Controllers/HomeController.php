<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Categorie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function HomePage(){
        return Inertia::render('HomePage');
    }
    function dashboardPage(Request $request){
        $user_id=$request->header('id');
        $product=Product::where('user_id',$user_id)->count();
        $category=Categorie::where('user_id',$user_id)->count();
        $customer=Customer::where('user_id',$user_id)->count();
        $invoice=Invoice::where('user_id',$user_id)->count();
        $total=Invoice::where('user_id',$user_id)->sum('total');
        $vat=Invoice::where('user_id',$user_id)->sum('vat');
        $payable=Invoice::where('user_id',$user_id)->sum('payable');
        $data=[
            'product'=>$product,
            'category'=>$category,
            'customer'=>$customer,
            'invoice'=>$invoice,
            'total'=>round($total,2),
            'vat'=>round($vat,2),
            'payable'=>round($payable,2)
        ];
        return Inertia::render('Users/DashboardPage')->with($data);
    }
}

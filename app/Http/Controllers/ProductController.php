<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    function productAddPage(Request $request){
        $user_id = $request->header('id');
        $product_id=$request->query('id');
        $product=Product::where('id', $product_id)->where('user_id', $user_id)->first();
        $categories = Categorie::where('user_id', $user_id)->get();
        return Inertia::render('Products/ProductAdd',['product'=>$product,'categories'=>$categories]);
        // return$product;
    }
    function productPage(Request $request)
    {
        $user_id = $request->header('id');
        $prducts = Product::where('user_id', $user_id)->with('categorie')->orderBy('id', 'desc')->get();
        return Inertia::render('Products/ProductPage')->with('products', $prducts);
    }
    function productList(Request $request)
    {
        $user_id = $request->header('id');
        if ($user_id !== null) {
            $Product = Product::where('user_id', $user_id)->get();
            if ($Product->isEmpty()) {
                return response()->json([
                    'message' => 'Your don\'t have any product yet!'
                ]);
            } else {
                return $Product;
            }
        } else {
            return response()->json([
                'message' => 'You are not login!'
            ]);
        }
    }
    function addProduct(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $img = $request->file('img');
            $validate = $request->validate([
                'categorie_id' => 'required',
                'name' => 'required',
                'price' => 'required',
                'unit' => 'required',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            //Genarate Image uniqu name

            $time = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$user_id}-{$time}-{$file_name}";
            $image_url = "product/{$img_name}";

            //Product Entry in database
            $addProduct = Product::create([
                'user_id' => $user_id,
                'categorie_id' => $validate['categorie_id'],
                'name' => $validate['name'],
                'price' => $validate['price'],
                'unit' => $validate['unit'],
                'image_url' => $image_url
            ]);

            if ($addProduct) {
                $img->move(public_path('product'), $img_name);
                $data = ['message' => 'Product Add Success', 'status' => true, 'error' => ''];
                return redirect()->route('product-page')->with($data);
            }
        } catch (Exception $e) {
            $data = ['message' => 'Product Add Failed', 'status' => false, 'error' => ''];
                return redirect()->route('product-page')->with($data);
        }


    }
    function deleteProduct(Request $request)
    {
        try {

            $user_id = $request->header('id');
            $product_id = $request->id;

            $findProduct = Product::where('id', $product_id)->where('user_id', $user_id)->first();
            if ($findProduct !== null) {
                $image_path = $findProduct->image_url;
                $findProduct->delete();
                File::delete($image_path);
                $data = ['message' => 'Product Delete Success', 'status' => true, 'error' => ''];
                return redirect()->route('product-page')->with($data);
            } else {
                $data = ['message' => 'Product Not Found', 'status' => false, 'error' => 'Database Error'];
                return redirect()->route('product-page')->with($data);
            }
        } catch (Exception $e) {
            $data = ['message' => 'Product Delete Failed', 'status' => false, 'error' => 'Database Error'];
            return redirect()->route('product-page')->with($data);
        }


    }
    function updateProduct(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $prodcut_id = $request->input('id');
            $findProduct = Product::where('id', $prodcut_id)->where('user_id', $user_id)->first();
            if (!$findProduct) {
                return response()->json([
                    'message' => 'There have no product'
                ]);
            } else {
                

                if($request->hasFile('img')) {

                    $validate = $request->validate([
                        'categorie_id' => 'nullable',
                        'name' => 'nullable',
                        'price' => 'nullable',
                        'unit' => 'nullable',
                        'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                    ]);

                    $img = $request->file('img');
                    $time = time();
                    $file_name = $img->getClientOriginalName();
                    $img_name = "{$user_id}-{$time}-{$file_name}";
                    $image_url = "product/{$img_name}";

                    if ($findProduct->image_url) {
                        File::delete($findProduct->image_url);
                    }
                    $addProduct = Product::where('id', $prodcut_id)->where('user_id', $user_id)->update([
                        'categorie_id' => $validate['categorie_id'],
                        'name' => $validate['name'],
                        'price' => $validate['price'],
                        'unit' => $validate['unit'],
                        'image_url' => $image_url
                    ]);

                    if ($addProduct) {
                        $img->move(public_path('product'), $img_name);
                    }
                } else {
                    $validate = $request->validate([
                        'categorie_id' => 'nullable',
                        'name' => 'nullable',
                        'price' => 'nullable',
                        'unit' => 'nullable'
                    ]);
                    Product::where('id', $prodcut_id)->where('user_id', $user_id)->update([
                        'categorie_id' => $validate['categorie_id'],
                        'name' => $validate['name'],
                        'price' => $validate['price'],
                        'unit' => $validate['unit']
                    ]);
                    
                }
                $data = ['message' => 'Product Update Success', 'status' => true, 'error' => ''];
                return redirect()->route('product-page')->with($data);
            }

        } catch (Exception $e) {
            $data = ['message' => $e->getMessage(), 'status' => false, 'error' => ''];
            return redirect()->route('product-add')->with($data);
        }

    }//end method
}

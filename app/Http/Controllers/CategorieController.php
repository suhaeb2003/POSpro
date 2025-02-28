<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Categorie;
use Exception;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    function categoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $category = Categorie::where('user_id', $user_id)->get();
        return Inertia::render('Category/CategoryPage')->with('categorys', $category);
    }

    function addCategoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->query('id');
        $category = Categorie::where('id', $id)->where('user_id', $user_id)->first();
        return Inertia::render('Category/CreateCategory')->with('category', $category);
    }
    function editCategoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->id;
        $category = Categorie::where('id', $id)->where('user_id', $user_id)->first();
        return Inertia::render('Category/CategoryUpdatePage')->with('category', $category);
    }
    function categorieList(Request $request)
    {
        $user_id = $request->header('id');
        if ($user_id !== null) {
            $categorie = Categorie::where('user_id', $user_id)->get();
            if ($categorie->isEmpty()) {
                return response()->json([
                    'message' => 'You have any categorie'
                ]);
            } else {
                return $categorie;
            }
        } else {
            return response()->json([
                'message' => 'You are not login!'
            ]);
        }
    }
    function createCategorie(Request $request)
    {

        $user_id = $request->header('id');
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Categorie::create([
                'name' => $request['name'],
                'user_id' => $user_id
            ]);
            $data = ['message' => 'Create Categorie Successfull', 'status' => true, 'error' => ''];
            return redirect()->route('add-category')->with($data);

        } catch (Exception $e) {
            $data = ['message' => 'Profile Update Successfull', 'status' => true, 'error' => ''];
            return redirect()->route('add-category')->with($data);
        }
    }
    function updateCategorie(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $categorie_id = $request->input('id');
            $name = $request->input('name');
            Categorie::where('id', $categorie_id)->where('user_id', $user_id)->update([
                'name' => $name
            ]);
            $data = ['message' => 'Category Update Successfull', 'status' => true,];
            return redirect()->route('add-category')->with($data);
        } catch (Exception $e) {
            $data = ['message' => 'Category Update Failed', 'status' => false, 'error' => ''];
            return redirect()->route('add-category')->with($data);
        }
    }
    function deleteCategorie(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $category_id = $request->id;
            Categorie::where('id', $category_id)->where('user_id', $user_id)->delete();
            $data = ['message' => 'Category Delete Successfull', 'status' => true,];
            return redirect()->route('category')->with($data);
        } catch (Exception $e) {
            $data = ['message' => 'Category Delete Failed', 'status' => false, 'error' => ''];
            return redirect()->route('category')->with($data);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'name'=>'required|max:191',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
        );

        $validator = Validator::make($input, $rules);

        if ($validator->fails())
        {

            return response()->json(['error' => $validator->errors()->getMessages()], 400);
        }


        $category = Category::create($input);

        $file = $request->file('image');

        if(isset($file))
        {
            $extension = $file->getClientOriginalExtension();
            $imageName = $category->id . "." . $extension;
            $path = $request->file('image')->storeAs('public/categories',$imageName );

            $photoPath = Storage::url($path);
            $photoUrl = request()->root().$photoPath;
            $category->image = $photoUrl;
            $category->save();
        }

         return responseJson(201,"category stored successfully",$category);

    }


    public function mostFiveCategories(Request $request)
    {

        $result = DB::table('products')
            ->join('categories','products.category_id','=','categories.id')
            ->groupBy('products.category_id')
            ->orderBy('products.category_id','Desc')
            ->select('categories.*')
            ->selectRaw("count(products.category_id) as most_5_products")
            ->limit(5)
            ->get();

        return responseJson(201,"data retrieved successfully",$result);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {

       $category = Category::find($id);

       if(!$category)
           return true;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}

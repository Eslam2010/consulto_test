<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return responseJson('202','Eslam',null);

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
            'category_id' => 'required',
            'expire_date' => 'required',
            'price'=>'required'
        );

        $validator = Validator::make($input, $rules);

        if ($validator->fails())
        {
            return response()->json(['error' => $validator->errors()->getMessages()], 400);
        }
        $input['expire_date'] =  \Carbon\Carbon::parse($input['expire_date'])->format('Y-m-d h:m:s');

        $product = Product::create($input);

        return responseJson(201,"product stored successfully",$product);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }


    public function highFivePriseProducts(Request $request,$id)
    {

        $result = DB::table('products')
            ->where('products.category_id','=',$id)
            ->orderBy('products.price','Desc')
            ->select('products.*')
            ->limit(5)
            ->get();

        return responseJson(201,"data retrieved successfully",$result);

    }

    public function deleteAllExpiredProduct(Request $request)
    {
        $currentDate  = $input['expire_date'] =  \Carbon\Carbon::now();
        $allExpired =DB::delete("DELETE from products where expire_date <= '".$currentDate."'");

        return responseJson(201,"all expired date delete successfully");

    }

    public function addImages(Request $request)
    {
       $productImages = [];

        $input = $request->all();
        $rules = array(
            'product_id'=>'required|max:191',
           // 'image' => 'mimes:jpeg,jpg,png,gif|required|max:1000000' // max 10000kb
        );

        $validator = Validator::make($input, $rules);

        if ($validator->fails())
        {

            return response()->json(['error' => $validator->errors()->getMessages()], 400);
        }



        if ($request->hasFile('image')) {

            $files = $request->file('image');

            foreach($files as $file) {
                $prdouctImage = ProductsImage::create([
                    'product_id'=>$input["product_id"]
                ]);

                $extension = $file->getClientOriginalExtension();
                $imageName = $prdouctImage->id . "." . $extension;
                $path = $file->storeAs('public/products',$imageName );


                $photoPath = Storage::url($path);
                $photoUrl = request()->root().$photoPath;
                $prdouctImage->image = $photoUrl;
                $prdouctImage->save();

                $productImages[]  = $prdouctImage;
               }
            }

        return responseJson(201,"category stored successfully",$productImages);

    }

    public function updateImagesOfProduct(Request $request,$id)
    {
        $input = $request->all();
        $rules = array(
          //  'name'=>'required|max:191',
            'image' => 'required|mimes:jpeg,jpg,png,gif' // max 10000kb
        );

        $validator = Validator::make($input, $rules);

        if ($validator->fails())
        {

            return response()->json(['error' => $validator->errors()->getMessages()], 400);
        }


        $productImage = ProductsImage::findORFail($id);
        $imagename = basename($productImage->image);
        unlink(storage_path('app/public/products/'.$imagename));


        $file = $request->file('image');

        if(isset($file))
        {
            unlink(storage_path('app/public/products/'.$imagename));
            $extension = $file->getClientOriginalExtension();
            $imageName = $productImage->id . "." . $extension;

            $path = $request->file('image')->storeAs('public/products',$imageName );

            $photoPath = Storage::url($path);
            $photoUrl = request()->root().$photoPath;
            $productImage->image = $photoUrl;
            $productImage->update([
                'image'=>$photoUrl
            ]);
        }

        return responseJson(201,"category stored successfully",$productImage);
    }

}

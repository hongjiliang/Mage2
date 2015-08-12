<?php

namespace App\Http\Controllers\Admin;

use App\Admin\CategoryProduct;
use App\Admin\Category;
use App\Admin\Product;
use App\Admin\Entity;
use App\Admin\Attribute;
use App\Admin\ProductsImage;
use App\Admin\ProductsPrice;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $products = Product::all();
        return view('admin.product.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $entity = Entity::Product()->get()->first();
        $categories = Category::lists('name', 'id');


        return view('admin.product.create')->with('entity', $entity)
            ->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {

        $product = Product::create($request->all());

        //Save Product Images
        $this->saveProductImages($request->get('productImage'), $product->id);

         //Save Product Images
        $this->saveProductPrices($request->get('price'), $product->id);
        //Save Product Categoryies
        $this->saveCategories($request->get('categories'), $product->id);

        //Save Product Attributes
        $this->saveAttribute($request->get('attribute'), $product->id);

        $product->slug = str_slug($request->get('name'));
        //update File Path and Slug
        $product->save();

        return redirect("/admin/product");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $entity = Entity::Product()->get()->first();
        $product = Product::findorfail($id);

        $productCategory = $product->category()->lists('category_id')->toArray();
        $price = $product->price()->get()->first();

        $productPrice = (isset($price)) ? $price->sale_price : 0;

        $categories = Category::lists('name', 'id');

        return view('admin.product.edit')->with('entity', $entity)
            ->with('product', $product)
            ->with('categories', $categories)
            ->with('productCategory', $productCategory)
            ->with('productPrice', $productPrice);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {


        $product = Product::find($id);
        $product->update($request->all());

        //Save Product Images
        $this->saveProductImages($request->get('productImage'), $id);

        //Save Product Images
        $this->saveProductPrices($request->get('price'), $id);

        //Save Product Categoryies
        $this->saveCategories($request->get('categories'), $id);

        //Save Product Attributes
        $attributes = $request->get('attribute');
        $this->saveAttribute($attributes, $id);


        if ($product->slug == "") {
            $product->slug = str_slug($request->get('name'));
            //update File Path and Slug
            $product->save();
        }


        return redirect("/admin/product");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }


    public function uploadProductImage(Request $request) {


        $relativePath = $this->uploadImage($request->file('file'), $for = 'product');

        return view('admin.product.upload-product-image')->with('imagePath', $relativePath)
                                ->with('randomString', str_random(6));
    }

    public function saveProductImages($images, $productId)
    {


        foreach ($images as $key => $imagePath) {
            if (is_int($key)) {
                continue;
            }
            $data['path'] = $imagePath;
            $data['product_id'] = $productId;

            ProductsImage::create($data);
        }
        return true;
    }

    public function saveCategories($categories, $productId)
    {

        CategoryProduct::where('product_id', $productId)->delete();

        foreach ($categories as $categoryId) {
            CategoryProduct::create(['category_id' => $categoryId, 'product_id' => $productId]);
        }
    }

    /*
     * Save Product Prices
     * @todo how to save multiple price with customer group and etc....
     *
     */
    public function saveProductPrices($price, $productId)
    {
        ProductsPrice::create(['product_id' => $productId, 'qty' => 1, 'sale_price' => $price]);
    }
}

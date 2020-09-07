<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
  public function index()
  {
    return response(Product::all()->jsonSerialize(), Response::HTTP_OK);
  }

  public function store(Request $request)
  {
    $Product = new Product();
    $Product->id = $request->id;
    $Product->name = $request->name;
    $Product->weight = $request->weight;
    $Product->price = $request->price;
    $Product->save();

    return response($Product->jsonSerialize(), Response::HTTP_CREATED);
  }

  public function update(Request $request, $id)
  {
    $Product = Product::findOrFail($id);
    $Product->name = $request->name;
    $Product->weight = $request->weight;
    $Product->price = $request->price;
    $Product->save();

    return response(null, Response::HTTP_OK);
  }

  public function destroy($id)
  {
    Product::destroy($id);

    return response(null, Response::HTTP_OK);
  }
}

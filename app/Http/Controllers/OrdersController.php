<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrdersController extends Controller
{
  public function index()
  {
    return response(Order::all()->jsonSerialize(), Response::HTTP_OK);
  }

  public function store(Request $request)
  {
    $Order = new Order();
    $Order->id = $request->id;
    $Order->idProducts = $request->idProducts;
    $Order->total_weight = $request->total_weight;
    $Order->total_price = $request->total_price;
    $Order->total = $request->total;
    $Order->status = $request->status;
    $Order->save();

    return response($Order->jsonSerialize(), Response::HTTP_CREATED);
  }

  public function update(Request $request, $id)
  {
    $Order = Order::findOrFail($id);
    $Order->idProducts = $request->idProducts;
    $Order->total_weight = $request->total_weight;
    $Order->total_price = $request->total_price;
    $Order->total = $request->total;
    $Order->status = $request->status;
    $Order->save();

    return response(null, Response::HTTP_OK);
  }

  public function destroy($id)
  {
    Order::destroy($id);

    return response(null, Response::HTTP_OK);
  }
}

<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderEmail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:order-list|Order-create|Order-edit|Order-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        $orders = Order::with('getProductHasOne')->latest()->paginate(5);
        return view('orders.index', compact('orders'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        return view('orders.create',\compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'order_id' => 'required|string|max:50',
            'product_id' => 'required',
            'total_amount' => 'required|numeric',
        ]);

        Order::create(['order_id' => $request->order_id,'product_id' => $request->product_id,'total_amount' => $request->total_amount,'user_id'=> auth()->user()->id]);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('Order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $products = Product::all();
        return view('orders.edit', compact('order','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        request()->validate([
            'order_id' => 'required|string|max:50',
            'product_id' => 'required',
            'total_amount' => 'required|numeric',
        ]);     

        // Email Data to Send
        $mail_data = [
            'from_email' => env('MAIL_FROM_ADDRESS', 'demo@yopmail.com'),
            'from_name' => env('APP_NAME', 'Test Project Name'),
            'to_email' => 'test@yopmail.com', // Auth::user()->email;
            'to_name' => 'Test Name', // Auth::user()->name;
            'title' => 'Order Status',
            'subject' => 'Order Updated',
            'body' => 'Your order no '.$request->order_id.' status has updates with '.$request->order_id,
        ];
        Mail::to($mail_data['to_email'])->send(new OrderEmail($mail_data));
        $order->update($request->all());
        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully');
    }
}

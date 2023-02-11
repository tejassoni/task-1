<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
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
            'name' => 'required|string|max:100',
            'detail' => 'required',
            'price' => 'required|numeric',
            'formFile' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $fileName = "";
        if ($request->hasFile('formFile')) {
            $filehandle = $this->_singleFileUploads($request, 'formFile', 'public/asset');
            if (isset($filehandle['status']) && $filehandle['status']) {
                $fileName = $filehandle['data']['filename'];                 
            }
        }

        Product::create(['name' => $request->name,'detail' => $request->detail,'price' => $request->price,'image' => $fileName,'user_id'=> auth()->user()->id]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
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

        if ($request->hasFile('formFile')) {
            $request->validate([
                'name' => 'required|string|max:100',
                'detail' => 'required',
                'price' => 'required|numeric',
                'formFile' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);

            $filehandle = $this->_singleFileUploads($request, 'formFile', 'public/asset');
            if (isset($filehandle['status']) && $filehandle['status']) {
                if(!empty($product->image) && file_exists(storage_path('app/public/asset/'.$product->image))){
                    unlink(storage_path('app/public/asset/'.$product->image));
                }                
                $product->image = $filehandle['data']['filename'];
            }
        } else {
            $request->validate([
                'name' => 'required|string|max:100',
                'detail' => 'required',
                'price' => 'required|numeric',
            ]);
        }
        
        $product->update(['name' => $request->name,'detail' => $request->detail,'price' => $request->price,'image' => $product->image,'user_id'=> auth()->user()->id]);
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }


         /**
     * _singleFileUploads : Complete Fileupload Handling
     * @param  Request $request
     * @param  $htmlformfilename : input type file name
     * @param  $uploadfiletopath : Public folder paths 'foldername/subfoldername'
     * @return File save with array return
     */
    private function _singleFileUploads($request = "", $htmlformfilename = "", $uploadfiletopath = "")
    {
        try {
            // check if folder exist at public directory if not exist then create folder 0777 permission
            if (!file_exists($uploadfiletopath)) {
                $oldmask = umask(0);
                mkdir($uploadfiletopath, 0777, true);
                umask($oldmask);
            }
            // check parameter empty Validation
            if(empty($request) || empty($htmlformfilename) || empty($uploadfiletopath)){
                    throw new \Exception("Required Parameters are missing", 400);
            }
            $fileNameOnly = preg_replace("/[^a-z0-9\_\-]/i", '', basename($request->file($htmlformfilename)->getClientOriginalName(), '.' . $request->file($htmlformfilename)->getClientOriginalExtension()));
            $fileFullName = $fileNameOnly . "_" . date('dmY') . "_" . time() . "." . $request->file($htmlformfilename)->getClientOriginalExtension();
            $path = $request->file($htmlformfilename)->storeAs($uploadfiletopath, $fileFullName);
            // $request->file($htmlformfilename)->move(public_path($uploadfiletopath), $fileFullName);
            $resp['status'] = true;
            $resp['data'] = array('filename' => $fileFullName, 'url' => url('storage/'.$uploadfiletopath.'/'.$fileFullName), 'fullpath' => \storage_path($path));
            $resp['message'] = "File uploaded successfully..!";
            return $resp;
        } catch (\Exception $ex) {
            $resp['status'] = false;
            $resp['data'] = [];
            $resp['message'] = 'File not uploaded...!';
            $resp['ex_message'] = $ex->getMessage();
            $resp['ex_code'] = $ex->getCode();
            $resp['ex_file'] = $ex->getFile();
            $resp['ex_line'] = $ex->getLine();
            return $resp;
        }
    }
}

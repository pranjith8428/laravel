<?php

namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Product;
 
class ProductController extends Controller
{


    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
            $products = $this->product->getPaginatedProducts(10);

            if ($request->ajax()) {
                return view('products.table', compact('products'))->render();
            }
    
            return view('products.index', compact('products'));
    }
  
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }
  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'price' => 'required|numeric',
            'product_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->product->createProduct($validatedData);
 
        return redirect()->route('products')->with('success', 'Product Added Successfully');
    }
  
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->product->getProductById($id);
  
        return view('products.show', compact('product'));
    }
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->product->getProductById($id);
  
        return view('products.edit', compact('product'));
    }
  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'price' => 'required|numeric',
            'product_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $product = $this->product->findOrFail($id);

        $product->updateProduct($validatedData);
  
        return redirect()->route('products')->with('success', 'Product Updated Successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->product->findOrFail($id);

        $product->deleteProduct();
  
        return redirect()->route('products')->with('success', 'Product Deleted Successfully');
    }
}

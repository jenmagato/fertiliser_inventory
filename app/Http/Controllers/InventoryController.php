<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    protected $InventoryService;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(InventoryService $InventoryService)
    {
        $this->InventoryService = $InventoryService;
    }

    /**
     * Process requested quantity
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validator to avoid unnecessary errors from user input
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect('/')->with('msg', 'Invalid input');;
        }

        //retrieve the validated input...
        $validatedData = $validator->validated();

        //process the requested quantity
        $data = $this->InventoryService->request($validatedData['quantity']);

        return redirect('/')->with($data);
    }
}

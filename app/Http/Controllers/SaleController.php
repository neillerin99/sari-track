<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}

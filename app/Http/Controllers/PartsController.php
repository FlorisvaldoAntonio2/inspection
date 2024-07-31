<?php

namespace App\Http\Controllers;

use App\Models\Parts;
use App\Http\Requests\StorePartsRequest;
use App\Http\Requests\UpdatePartsRequest;

class PartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Parts $parts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parts $parts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartsRequest $request, Parts $parts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parts $parts)
    {
        //
    }
}

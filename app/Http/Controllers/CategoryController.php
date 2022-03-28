<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return category::latest()->get();
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
        $this->validate($request, 
            [
                'name' => 'required',
                'hidden' =>  'numeric|min:0|max:1'
            ],
            [
                'name.required' => 'Название обязательно к заполнению!',
                'hidden.numeric' => 'Только 0 или 1!',
                'hidden.min' => 'Только 0 или 1!',
                'hidden.max' => 'Только 0 или 1!'
            ]
        );
        category::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = category::findOrFail($id);
        $this->validate($request, 
            [
                'name' => 'required',
                'hidden' =>  'numeric|min:0|max:1'
            ],
            [
                'name.required' => 'Название обязательно к заполнению!',
                'hidden.numeric' => 'Только 0 или 1!',
                'hidden.min' => 'Только 0 или 1!',
                'hidden.max' => 'Только 0 или 1!'
            ]
        );
        $book->update($request->all());
        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = category::findOrFail($id);
        $book->delete();
        return category::latest()->get();
    }
}

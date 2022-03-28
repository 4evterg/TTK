<?php

namespace App\Http\Controllers;

use App\Models\books;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return books::oldest()->get();
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
                'publish_year' => 'numeric|max:9999'
            ],
            [
                'name.required' => 'Название обязательно к заполнению!',
                
                'publish_year.numeric' => 'Только цифры!'
            ]
        );
        $requestData = $request->all();
        if($request->file()) {
            
            $this->validate($request, [
                'cover' => 'min:0|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:512'
            ]);

            $file_name = time().'_'.$request->cover->getClientOriginalName();

            $file_path = $request->file('cover')->storeAs('uploads', $file_name, 'public');
            $cover = '/storage/' . $file_path;
            $requestData = $request->all();
            $requestData['cover'] = $cover;
        }
        books::create($requestData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\books  $books
     * @return \Illuminate\Http\Response
     */
    public function show(books $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\books  $books
     * @return \Illuminate\Http\Response
     */
    public function edit(books $books)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\books  $books
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Books::findOrFail($id);
        $this->validate($request, 
            [
                'name' => 'required',
                'publish_year' => 'numeric|max:9999',
                'hidden' =>  'numeric|min:0|max:1',

            ],
            [
                'name.required' => 'Название обязательно к заполнению!',                
                'publish_year.numeric' => 'Только цифры!',
                'hidden.numeric' => 'Только 0 или 1!',
                'hidden.min' => 'Только 0 или 1!',
                'hidden.max' => 'Только 0 или 1!',
            ]
        );

        $requestData = $request->all();
        if($request->file()) {
            
            $this->validate($request, [
                'cover' => 'min:0|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:512'
            ]);

            $file_name = time().'_'.$request->cover->getClientOriginalName();

            $file_path = $request->file('cover')->storeAs('uploads', $file_name, 'public');
            $book->cover = '/storage/' . $file_path;
            $requestData = $request->all();
            $requestData['cover'] = $book->cover;
        }

        $book->update($requestData);
        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\books  $books
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Books::findOrFail($id);
        $book->delete();
        return Books::latest()->get();
    }
}

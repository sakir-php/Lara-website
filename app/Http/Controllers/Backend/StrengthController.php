<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Strength;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Facades\DataTables;

class StrengthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $data = Strength::all();
            return datatables::of($data)
                ->addColumn('action', function($data) {
                    return '<a href="'.route('backend.strength.edit', $data).'" class="btn btn-info"><i class="fas fa-edit"></i> </a>
                   <button class="btn btn-danger" onclick="delete_function(this)" value="'.route('backend.strength.destroy', $data).'"><i class="fas fa-trash"></i> </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }else{
            return view('backend.website.strength.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website.strength.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'percentage' => 'required|numeric',
        ]);
        $strength = new Strength();

        $strength->name    =   $request->name;
        $strength->percentage    =  $request->percentage;

        try {
            $strength->save();
            return back()->withToastSuccess('Successfully saved.');
        }catch (\Exception $exception){
            return back()->withErrors('Something going wrong. '.$exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Strength  $strength
     * @return \Illuminate\Http\Response
     */
    public function show(Strength $strength)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Strength  $strength
     * @return \Illuminate\Http\Response
     */
    public function edit(Strength $strength)
    {
        return view('backend.website.strength.edit', compact('strength'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Strength  $strength
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Strength $strength)
    {
        $request->validate([
            'name' => 'required|string',
            'percentage' => 'required|numeric',
        ]);

        $strength->name    =   $request->name;
        $strength->percentage    =  $request->percentage;

        try {
            $strength->save();
            return back()->withToastSuccess('Successfully updated.');
        }catch (\Exception $exception){
            return back()->withErrors('Something going wrong. '.$exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Strength  $strength
     * @return \Illuminate\Http\Response
     */
    public function destroy(Strength $strength)
    {
        try {
            $strength->delete();
            return response()->json([
                'type' => 'success',
            ]);
        }catch (\Exception$exception){
            return response()->json([
                'type' => 'error',
            ]);
        }
    }

    public function strength(){
        return view('backend.website.strength.strength');
    }

    public function strengthUpdate(Request $request){
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
        ]);
        try {
            update_static_option('service_title', $request->title);
            update_static_option('service_description', $request->description);

            return back()->withToastSuccess('Successfully updated.');
        }catch (\Exception $exception){
            return back()->withErrors('Something going wrong. '.$exception->getMessage());
        }
    }
}
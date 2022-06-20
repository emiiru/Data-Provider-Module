<?php

namespace App\Http\Controllers;

use App\Models\DataProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;


class DataProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DataProvider::select('id','name','url')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = "<button type='button' class='btn btn-primary btn-view btn-sm' data-id='$row->id'><span class='fa fa-eye'></span> View</button>
                    <button type='button' class='btn btn-warning btn-sm btn-edit' data-id='$row->id'><span class='fa fa-pencil'></span> Edit</button>
                    <button type='button' class='btn btn-danger btn-sm btn-delete' data-id='$row->id'><span class='fa fa-trash'></span> Delete</button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // validate
        $validatedData = $request->validate([
            'name' => 'required',
            'url' => 'required|url'
        ]);

        $data = new DataProvider();
        $data->name = $request->name;;
        $data->url = $request->url;;
        $data->save();
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataProvider  $dataProvider
     * @return \Illuminate\Http\Response
     */
    public function show($id, DataProvider $dataProvider)
    {
        $data = $dataProvider->select('id','name','url')->find($id);
        return response()->json($data, 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataProvider  $dataProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataProvider $dataProvider)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'url' => 'required|url'
        ]);

        $data = $dataProvider->find($request->id);
        $data->name = $request->name;;
        $data->url = $request->url;;
        $data->save();
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataProvider  $dataProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataProvider $dataProvider, $id)
    {
        $data = $dataProvider->findOrFail($id);
        $data->delete();
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DataProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use DataTables;


class DataProviderController extends Controller
{
    
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
                ->toJson();
        }
        return view('index');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'url' => 'required|url'
        ]);
        DataProvider::create($validatedData);

        // Return a success response
        return response()->json(['status' => 'success'], 200);
    }

    public function show($id)
    {
        $data = DataProvider::findOrFail($id, ['id', 'name', 'url']);
        return response()->json($data, 200);
    }

    public function update(Request $request, DataProvider $dataProvider)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'url' => 'required|url'
        ]);

        $data = $dataProvider->findOrFail($request->id);
        $data->fill($request->only(['name', 'url']));
        $data->save();

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function destroy(DataProvider $dataProvider, $id)
    {
        $data = $dataProvider->findOrFail($id);
        $data->delete();
    
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}

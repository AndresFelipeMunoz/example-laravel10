<?php

namespace App\Http\Controllers;
use App\Models\Employee; 
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            return Datatables()->of(Employee::select('*'))
                ->addColumn('action', 'employee-action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('index');
    }
    public function store(Request $request)
    {
       
        // Validar los datos
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'address' => 'required|max:50',
        ]);

        // Crear o actualizar empleado
        Employee::updateOrCreate(
            ['id' => $request->id], // Si hay un ID, actualiza; de lo contrario, crea
            [
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address
            ]
        );

        // Retornar respuesta JSON
        return response()->json(['success' => 'Employee saved successfully.']);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $employee = Employee::where($where)->first();

        return response()->json($employee);

    }

    public function destroy(Request $request)
    {
        $employee = Employee::where('id', $request->id)->delete();


        return response()->json($employee);
    }
}

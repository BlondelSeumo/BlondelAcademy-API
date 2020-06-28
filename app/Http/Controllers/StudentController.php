<?php

namespace App\Http\Controllers;

use App\ClassRoom;
use App\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'data' => Student::query()
                ->with(['currentClass'])
                ->orderBy('first_name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'class_id' => 'required|exists:class_rooms,id',
            'birth_date' => 'required|date',
        ]);

        $data = $validator->validated();

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        /** @var ClassRoom $class */
        $class = ClassRoom::query()
            ->with(['students'])
            ->where('id', $data['class_id'])
            ->first();
        if ($class->maximum_students <= $class->students()->count()) {
            return response()->json([
                'error' => 'Max students count for class ' . $class->name . ' reached. Please contact an administrator to raise this limit'
            ], 403);
        }

        return response()->json([
            'data' => Student::create($data)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return Response
     */
    public function show(Student $student)
    {
        return response()->json([
            'data' => $student
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Student  $student
     * @return JsonResponse
     */
    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'class_id' => 'required|exists:class_rooms,id',
            'birth_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $validator->validated();

        if ($data['class_id'] !== $student->class_id) {
            /** @var ClassRoom $class */
            $class = ClassRoom::query()
                ->with(['students'])
                ->where('id', $data['class_id'])
                ->first();
            if ($class->maximum_students <= $class->students()->count()) {
                return response()->json([
                    'error' => 'Max students count for class ' . $class->name . ' reached. Please contact an administrator to raise this limit'
                ], 403);
            }
        }
        $student->fill($data);
        $student->save();

        return response()->json(['data' => $student->fresh()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Student $student
     * @return void
     * @throws \Exception
     */
    public function destroy(Student $student)
    {
        if ($student) {
            $student->delete();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\ClassRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'data' => ClassRoom::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
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
            'name' => 'required|unique:class_rooms,name',
            'code' => 'nullable|unique:class_rooms,code',
            'description' => 'nullable',
            'status' => 'required|in:opened,closed',
            'maximum_students' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $data = $validator->validated();

        return response()->json([
            'data' => ClassRoom::create(
                array_merge($data, [
                    'code' => $data['code'] ?? Str::slug($data['name'])
                ])
            )
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param ClassRoom $class
     * @return JsonResponse
     */
    public function show(ClassRoom $class)
    {
        return response()->json([
            'data' => ClassRoom::query()->with(['students'])->where('id', $class->id)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ClassRoom $classRoom
     * @return Response
     */
    public function edit(ClassRoom $classRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ClassRoom $class
     * @return JsonResponse
     */
    public function update(Request $request, ClassRoom $class)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:class_rooms,name,' . $class->id,
            'code' => 'nullable|unique:class_rooms,code,' . $class->id,
            'description' => 'nullable',
            'status' => 'required|in:opened,closed',
            'maximum_students' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $data = $validator->validated();

        $class->fill(Arr::except($data, ['id']));

        $class->save();

        return response()->json(['data' => $class->fresh()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ClassRoom $class
     * @return void
     * @throws \Exception
     */
    public function destroy(ClassRoom $class)
    {
        if ($class) {
            $class->delete();
        }
    }
}

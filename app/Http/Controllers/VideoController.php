<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => Video::all()]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv',
            'name' => 'required'
        ]);

        $data = $validator->validated();


        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        /** @var File $file */
        $file = $request->files->get('file');
        $name = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = 'uploads/videos';
        $file->move($destinationPath, $name);

        return response()->json(['data' => Video::create(
            array_merge($data, [
                'path' => $destinationPath . '/' . $name
            ])
        )]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return JsonResponse
     */
    public function show(Video $video)
    {
        if ($video) {
            $video->path = env('APP_URL') . '/' . $video->path;
        }
        return response()->json([
            'data' => $video
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Video  $video
     * @return Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return Response
     */
    public function destroy(Video $video)
    {
        //
    }
}

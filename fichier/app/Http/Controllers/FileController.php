<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the files.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(File::all(), 200);
    }

    /**
     * Store a newly created file in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|file',
        ]);

        $path = $request->file('file_path')->store('files');

        $file = File::create([
            'user_id' => $request->user_id,
            'file_name' => $request->file_name,
            'file_path' => $path,
        ]);

        return response()->json($file, 201);
    }

    /**
     * Display the specified file.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $file = File::findOrFail($id);
        return response()->json($file, 200);
    }

    /**
     * Update the specified file in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);

        $request->validate([
            'file_name' => 'required|string|max:255',
            'file_path' => 'nullable|file',
        ]);

        if ($request->hasFile('file_path')) {
            Storage::delete($file->file_path);
            $path = $request->file('file_path')->store('files');
            $file->file_path = $path;
        }

        $file->file_name = $request->file_name;
        $file->save();

        return response()->json($file, 200);
    }

    /**
     * Remove the specified file from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $file = File::findOrFail($id);
        Storage::delete($file->file_path);
        $file->delete();
        return response()->json(null, 204);
    }

    /**
     * Approve the specified file.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, $id)
    {
        $file = File::findOrFail($id);

        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $file->update(['is_approved' => true]);

        return response()->json($file, 200);
    }
}

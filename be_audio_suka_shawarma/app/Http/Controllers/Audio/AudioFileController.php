<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audio\AudioFileRequest;
use App\Service\Audio\AudioFileService;
use Illuminate\Http\Request;

class AudioFileController extends Controller
{
    public function __construct(
        protected AudioFileService $audioFileService
    ) {}

    public function stream(string $filename)
    {
        $path = storage_path(
            'app/public/audio/' . $filename
        );

        if (!file_exists($path)) {
            abort(404, 'Audio tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type' => 'audio/mpeg',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
            'Accept-Ranges' => 'bytes',
        ]);
    }

    /**
     * List audio.
     */
    public function index()
    {
        $result = $this->audioFileService->index();

        return response()->json([
            'message' => 'Data audio berhasil diambil',
            'data' => $result,
        ]);
    }

    /**
     * Detail audio.
     */
    public function show(int $id)
    {
        $result = $this->audioFileService->show($id);

        return response()->json([
            'message' => 'Data audio berhasil diambil',
            'data' => $result,
        ]);
    }

    /**
     * Upload audio.
     */
    public function upload(AudioFileRequest $request)
    {
        $result = $this->audioFileService->upload(
            $request->file('file')
        );

        return response()->json([
            'message' => 'Audio berhasil di-upload',
            'data' => $result,
        ], 201);
    }

    /**
     * Hapus audio.
     */
    public function destroy(int $id)
    {
        $this->audioFileService->delete($id);

        return response()->json([
            'message' => 'Audio berhasil dihapus',
        ]);
    }
}

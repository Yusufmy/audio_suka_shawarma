<?php

namespace App\Service\Audio;

use App\Models\AudioFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AudioFileService
{

    /**
     * Mengambil semua audio.
     */
    public function index(): array
    {
        $operator = JWTAuth::parseToken()->authenticate();

        if (!$operator) {
            throw new UnauthorizedHttpException(
                'Bearer',
                'Operator tidak terautentikasi.'
            );
        }

        $audioFiles = AudioFile::query()
            ->latest()
            ->get();

        return $audioFiles
            ->map(fn(AudioFile $audioFile) => $this->formatAudio($audioFile))
            ->values()
            ->toArray();
    }

    /**
     * Mengambil detail audio berdasarkan ID.
     */
    public function show(int $id): array
    {
        $operator = JWTAuth::parseToken()->authenticate();

        if (!$operator) {
            throw new UnauthorizedHttpException(
                'Bearer',
                'Operator tidak terautentikasi.'
            );
        }

        $audioFile = AudioFile::find($id);

        if (!$audioFile) {
            throw new NotFoundHttpException(
                'Audio tidak ditemukan.'
            );
        }

        return $this->formatAudio($audioFile);
    }

    /**
     * Upload audio file.
     */
    public function upload(UploadedFile $file): array
    {
        // Ambil operator yang sedang login
        $operator = JWTAuth::parseToken()->authenticate();

        if (!$operator) {
            throw new UnauthorizedHttpException(
                'Bearer',
                'Operator tidak terautentikasi.'
            );
        }

        // Simpan file ke storage/app/public/audio
        $filePath = $file->store(
            'audio',
            'public'
        );

        // Simpan informasi audio ke database
        $audioFile = AudioFile::create([
            'operator_id' => $operator->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'size_bytes' => $file->getSize(),
            'duration_seconds' => null,
        ]);

        return [
            'id' => $audioFile->id,
            'original_name' => $audioFile->original_name,
            'file_path' => $audioFile->file_path,
            // 'url' => asset('storage/' . $audioFile->file_path),
            'url' => url('api/audio-stream/' . basename($audioFile->file_path)),
            'duration_seconds' => $audioFile->duration_seconds,
            'size_bytes' => $audioFile->size_bytes,
            'created_at' => $audioFile->created_at,
        ];
    }

    /**
     * Menghapus audio.
     */
    public function delete(int $id): void
    {
        $operator = JWTAuth::parseToken()->authenticate();

        if (!$operator) {
            throw new UnauthorizedHttpException(
                'Bearer',
                'Operator tidak terautentikasi.'
            );
        }

        $audioFile = AudioFile::find($id);

        if (!$audioFile) {
            throw new NotFoundHttpException(
                'Audio tidak ditemukan.'
            );
        }

        /*
         * Hapus file fisik dari storage.
         */
        if (
            $audioFile->file_path &&
            Storage::disk('public')->exists($audioFile->file_path)
        ) {
            Storage::disk('public')->delete(
                $audioFile->file_path
            );
        }

        /*
         * Hapus record dari database.
         */
        $audioFile->delete();
    }

    /**
     * Format response audio.
     */
    private function formatAudio(AudioFile $audioFile): array
    {
        return [
            'id' => $audioFile->id,
            'original_name' => $audioFile->original_name,
            'file_path' => $audioFile->file_path,
            'url' => url(
                'api/audio-stream/' . basename($audioFile->file_path)
            ),
            'duration_seconds' => $audioFile->duration_seconds,
            'size_bytes' => $audioFile->size_bytes,
            'created_at' => $audioFile->created_at,
        ];
    }
}

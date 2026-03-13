<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serves protected documentation assets from storage.
 * Files are retrieved from a non-public directory and delivered only after passing through
 * authentication/feature middleware applied at the route level.
 */
class ProtectedFileController extends Controller
{
    /**
     * Display a protected file (PNG/PDF etc.) from the private documentation directory.
     *
     * @param string $filename The requested file name; expected to be validated/restricted by route patterns.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException  If the file does not exist.
     */
    public function show(string $filename): BinaryFileResponse
    {
        // Normalise the input to prevent directory traversal (e.g. strip any path components).
        $safeName = basename($filename);

        // Resolve the full path under the private documentation storage area.
        $path = storage_path('app/private/documentation/' . $safeName);

        // Fail early if the target file is missing.
        if (! File::exists($path)) {
            abort(404);
        }

        // Placeholder: additional per-user authorisation logic can be inserted here if needed.

        // Determine the MIME type for correct Content-Type header; fallback to a generic binary if detection fails.
        $mime = File::mimeType($path) ?: 'application/octet-stream';

        // Serve the file inline with its original filename.
        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $safeName . '"',
        ]);
    }
}

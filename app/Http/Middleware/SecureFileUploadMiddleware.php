<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class SecureFileUploadMiddleware
{
    /**
     * The allowed MIME types for file uploads
     */
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        // Add any other allowed types as needed
    ];

    /**
     * The maximum file size in bytes (2MB)
     */
    protected $maxFileSize = 2097152; // 2MB

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request has file uploads
        if ($request->hasFile('image') || $request->hasFile('heroimage') ||
            $request->hasFile('profilepic') || $request->hasAny(['files', 'file'])) {

            $files = [];

            // Collect all file inputs
            foreach (['image', 'heroimage', 'profilepic', 'file'] as $inputName) {
                if ($request->hasFile($inputName)) {
                    $files[$inputName] = $request->file($inputName);
                }
            }

            // Handle array of files
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    $files["files.{$index}"] = $file;
                }
            }

            // Validate each file
            foreach ($files as $inputName => $file) {
                // Check if file is valid
                if (!$file->isValid()) {
                    return response()->json([
                        'error' => "Upload failed for {$inputName}. The file was not uploaded properly."
                    ], 400);
                }

                // Check file size
                if ($file->getSize() > $this->maxFileSize) {
                    return response()->json([
                        'error' => "File {$inputName} exceeds maximum size of 2MB."
                    ], 400);
                }

                // Check MIME type
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return response()->json([
                        'error' => "File type not allowed for {$inputName}. Allowed types: JPEG, PNG, GIF, WebP, PDF."
                    ], 400);
                }

                // Generate a safe filename
                $originalName = $file->getClientOriginalName();
                $safeFilename = pathinfo($originalName, PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '', $safeFilename);
                $safeFilename = $safeFilename . '_' . time() . '_' . random_int(1000, 9999) . '.' . $file->getClientOriginalExtension();

                // Replace the uploaded file with the safely named version
                $request->files->set($inputName, $file->move(
                    sys_get_temp_dir(),
                    $safeFilename
                ));

                Log::info("Secure file upload processed", [
                    'original_name' => $originalName,
                    'safe_name' => $safeFilename,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        return $next($request);
    }
}

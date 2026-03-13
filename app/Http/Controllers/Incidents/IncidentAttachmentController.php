<?php

namespace App\Http\Controllers\Incidents;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncidentAttachmentController extends Controller
{
    /**
     * Handle uploaded files and return attachment data.
     */
    public function handleUploadedFiles(Request $request): array
    {
        $attachmentPaths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('incident-attachments');
                $attachmentPaths[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        return $attachmentPaths;
    }

    /**
     * View an attachment inline (for images and PDFs).
     */
    public function viewAttachment(IncidentReport $incident, $index)
    {
        $attachments = $incident->attachments ?? [];

        if (!isset($attachments[$index])) {
            abort(404, 'Attachment not found');
        }

        $attachment = $attachments[$index];

        if (!Storage::exists($attachment['path'])) {
            abort(404, 'Attachment file not found');
        }

        $mimeType = $attachment['mime'] ?? Storage::mimeType($attachment['path']);

        return response()->file(
            Storage::path($attachment['path']),
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . ($attachment['name'] ?? 'attachment') . '"'
            ]
        );
    }

    /**
     * Download an attachment.
     */
    public function downloadAttachment(IncidentReport $incident, $index)
    {
        $attachments = $incident->attachments ?? [];

        if (!isset($attachments[$index])) {
            abort(404, 'Attachment not found');
        }

        $attachment = $attachments[$index];

        if (!Storage::exists($attachment['path'])) {
            abort(404, 'Attachment file not found');
        }

        return Storage::download(
            $attachment['path'],
            $attachment['name'] ?? 'attachment'
        );
    }

    /**
     * Remove a specific attachment from an incident.
     */
    public function removeAttachment(IncidentReport $incident, $index)
    {
        $attachments = $incident->attachments ?? [];

        if (isset($attachments[$index])) {
            if (isset($attachments[$index]['path'])) {
                Storage::delete($attachments[$index]['path']);
            }

            unset($attachments[$index]);
            $attachments = array_values($attachments); // Re-index array

            $incident->update([
                'attachments' => $attachments,
                'updated_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Attachment removed successfully.');
    }

    /**
     * Clean up attachment files from storage.
     */
    public function cleanupAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            if (isset($attachment['path'])) {
                Storage::delete($attachment['path']);
            }
        }
    }
}

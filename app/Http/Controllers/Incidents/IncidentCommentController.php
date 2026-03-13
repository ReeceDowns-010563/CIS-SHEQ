<?php

namespace App\Http\Controllers\Incidents;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\IncidentComment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class IncidentCommentController extends Controller
{
    /**
     * Get comments for a specific incident.
     */
    public function index(IncidentReport $incident): JsonResponse
    {
        $comments = $incident->comments()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->format('M d, Y \a\t g:i A'),
                    'can_delete' => Auth::id() === $comment->user_id || Auth::user()->hasRole('admin'),
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'count' => $comments->count(),
        ]);
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request, IncidentReport $incident): JsonResponse
    {
        try {
            $validated = $request->validate([
                'comment' => 'required|string|max:1000',
            ]);

            $comment = IncidentComment::create([
                'incident_id' => $incident->id,
                'user_id' => Auth::id(),
                'comment' => $validated['comment'],
            ]);

            $comment->load('user:id,name');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->format('M d, Y \a\t g:i A'),
                    'can_delete' => true,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Delete a comment.
     */
    public function destroy(IncidentReport $incident, IncidentComment $comment): JsonResponse
    {
        // Check if user can delete the comment
        if (Auth::id() !== $comment->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this comment',
            ], 403);
        }

        // Verify comment belongs to the incident
        if ($comment->incident_id !== $incident->id) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found for this incident',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}

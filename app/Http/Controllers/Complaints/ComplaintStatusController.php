<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Complaint Status Controller
 *
 * Handles complaint status updates and assignment changes.
 * Provides both AJAX endpoints and form submission handlers.
 */
class ComplaintStatusController extends Controller
{
    /**
     * Update the complaint status (AJAX)
     *
     * @param Request $request
     * @param Complaint $complaint
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        if ($complaint->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update status of archived complaints.'
            ], 400);
        }

        $request->validate([
            'status' => 'required|string|in:open,closed'
        ]);

        $this->performStatusUpdate($complaint, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'date_concluded' => $complaint->date_concluded,
            'status' => $complaint->status
        ]);
    }

    /**
     * Update the complaint status (Form submission)
     *
     * @param Request $request
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatusForm(Request $request, Complaint $complaint)
    {
        if ($complaint->archived) {
            return redirect()->back()
                ->with('error', 'Cannot update status of archived complaints.');
        }

        $request->validate([
            'status' => 'required|string|in:open,closed'
        ]);

        $this->performStatusUpdate($complaint, $request->status);

        // Determine redirect based on where the request came from
        $redirectRoute = $this->determineRedirectRoute($request);

        return redirect()->route($redirectRoute)
            ->with('success', 'Status updated successfully.');
    }

    /**
     * Update the assignment of a complaint (AJAX)
     *
     * @param Request $request
     * @param Complaint $complaint
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAssignment(Request $request, Complaint $complaint)
    {
        if ($complaint->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update assignment of archived complaints.'
            ], 400);
        }

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $this->performAssignmentUpdate($complaint, $request->assigned_to);

        return response()->json([
            'success' => true,
            'message' => 'Assignment updated successfully.',
            'assigned_to_id' => $complaint->assigned_to,
            'assigned_to_name' => $complaint->assignedTo ? $complaint->assignedTo->name : 'Unassigned'
        ]);
    }

    /**
     * Update the assignment of a complaint (Form submission)
     *
     * @param Request $request
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAssignmentForm(Request $request, Complaint $complaint)
    {
        if ($complaint->archived) {
            return redirect()->back()
                ->with('error', 'Cannot update assignment of archived complaints.');
        }

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $this->performAssignmentUpdate($complaint, $request->assigned_to);

        // Determine redirect based on where the request came from
        $redirectRoute = $this->determineRedirectRoute($request);

        return redirect()->route($redirectRoute)
            ->with('success', 'Assignment updated successfully.');
    }

    /**
     * Perform the actual status update logic
     *
     * @param Complaint $complaint
     * @param string $newStatus
     */
    private function performStatusUpdate(Complaint $complaint, string $newStatus): void
    {
        $oldStatus = $complaint->status;

        // Log the status change if different
        if ($oldStatus !== $newStatus) {
            $this->logStatusChange($complaint, $oldStatus, $newStatus);
        }

        // Use the model's mutator to automatically handle date_concluded
        $complaint->status = $newStatus;
        $complaint->save();
        $complaint->refresh();
    }

    /**
     * Perform the actual assignment update logic
     *
     * @param Complaint $complaint
     * @param int|null $newAssignment
     */
    private function performAssignmentUpdate(Complaint $complaint, ?int $newAssignment): void
    {
        $oldAssignment = $complaint->assigned_to;
        $newAssignment = $newAssignment ?: null;

        // Log the assignment change if different
        if ($oldAssignment != $newAssignment) {
            $this->logAssignmentChange($complaint, $oldAssignment, $newAssignment);
        }

        $complaint->update([
            'assigned_to' => $newAssignment
        ]);

        $complaint->refresh();
    }

    /**
     * Determine redirect route based on the request context
     *
     * @param Request $request
     * @return string
     */
    private function determineRedirectRoute(Request $request): string
    {
        $referer = $request->headers->get('referer', '');

        if (str_contains($referer, 'my-investigations')) {
            return 'complaints.my-investigations';
        }

        return 'complaints.manage';
    }

    /**
     * Log status changes
     *
     * @param Complaint $complaint
     * @param string $oldStatus
     * @param string $newStatus
     */
    private function logStatusChange(Complaint $complaint, string $oldStatus, string $newStatus): void
    {
        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action_type' => 'status_updated',
            'changes' => [
                'status' => [
                    'from' => $oldStatus,
                    'to' => $newStatus
                ]
            ]
        ]);
    }

    /**
     * Log assignment changes
     *
     * @param Complaint $complaint
     * @param int|null $oldAssignment
     * @param int|null $newAssignment
     */
    private function logAssignmentChange(Complaint $complaint, ?int $oldAssignment, ?int $newAssignment): void
    {
        $oldUser = $oldAssignment ? User::find($oldAssignment) : null;
        $newUser = $newAssignment ? User::find($newAssignment) : null;

        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action_type' => 'assignment_updated',
            'changes' => [
                'assigned_to' => [
                    'from' => $oldUser ? $oldUser->name : 'Unassigned',
                    'to' => $newUser ? $newUser->name : 'Unassigned'
                ]
            ]
        ]);
    }
}

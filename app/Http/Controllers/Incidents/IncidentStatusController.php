<?php

namespace App\Http\Controllers\Incidents;

use App\Http\Controllers\Controller;
use App\Mail\AccidentCompletedMail;
use App\Mail\CoordinatorAssignedMail;
use App\Models\CorrectiveAction;
use App\Models\EmailTemplate;
use App\Models\IncidentLog;
use App\Models\IncidentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IncidentStatusController extends Controller
{
    /**
     * Update incident status with corrective actions
     */
    public function updateStatusWithCorrectiveActions(Request $request, IncidentReport $incident)
    {
        if ($incident->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update status of archived incidents.'
            ], 400);
        }

        $request->validate([
            'status' => 'required|string|in:completed,closed',
            'corrective_actions' => 'required|string|min:10|max:2000'
        ]);

        try {
            $oldStatus = $incident->status;
            $newStatus = $request->status;

            DB::beginTransaction();

            // Update incident status
            $incident->update([
                'status' => $newStatus,
                'updated_by' => Auth::id(),
            ]);

            // Create corrective action with proper polymorphic relationship
            CorrectiveAction::create([
                'actionable_type' => IncidentReport::class,
                'actionable_id' => $incident->id,
                'corrective_actions' => $request->corrective_actions,
                'user_id' => Auth::id(),
                'status' => $newStatus,
            ]);

            // Log the status change
            IncidentLog::logStatusChange($incident->id, Auth::id(), $oldStatus, $newStatus);

            DB::commit();

            // Send completion email if status changed to completed or closed
            if (in_array($newStatus, ['completed', 'closed']) && !in_array($oldStatus, ['completed', 'closed'])) {
                $this->sendCompletedEmail($incident);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully with corrective actions'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update incident status
     */
    public function updateStatus(Request $request, IncidentReport $incident)
    {
        if ($incident->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update status of archived incidents.'
            ], 400);
        }

        $request->validate([
            'status' => 'required|string|in:open,investigating,closed'
        ]);

        try {
            $oldStatus = $incident->status;
            $newStatus = $request->status;

            $incident->update([
                'status' => $newStatus,
                'updated_by' => Auth::id(),
            ]);

            // Log the status change
            IncidentLog::logStatusChange($incident->id, Auth::id(), $oldStatus, $newStatus);

            // Send completion email if status changed to completed or closed
            if (in_array($newStatus, ['completed', 'closed']) && !in_array($oldStatus, ['completed', 'closed'])) {
                $this->sendCompletedEmail($incident);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => ucfirst($newStatus)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send completion email with investigation PDF when incident is marked as completed or closed.
     */
    private function sendCompletedEmail(IncidentReport $incident): void
    {
        try {
            Log::info('=== Starting to send completion email ===', [
                'incident_id' => $incident->id,
                'status' => $incident->status
            ]);

            // Check if all corrective actions are completed
            $totalActions = $incident->correctiveActions()->count();
            $completedActions = $incident->correctiveActions()->where('status', 'completed')->count();

            Log::info('Corrective actions check', [
                'incident_id' => $incident->id,
                'total_actions' => $totalActions,
                'completed_actions' => $completedActions
            ]);

            $allCorrectiveActionsCompleted = $incident->correctiveActions()
                ->where('status', '!=', 'completed')
                ->doesntExist();

            if (!$allCorrectiveActionsCompleted) {
                Log::warning('=== EMAIL NOT SENT - Corrective actions incomplete ===', [
                    'incident_id' => $incident->id,
                    'total_actions' => $totalActions,
                    'completed_actions' => $completedActions,
                    'message' => 'Complete all corrective actions to receive email notification'
                ]);
                return;
            }

            // Get email template
            $emailTemplate = EmailTemplate::where('key', 'accident_completed')
                ->where('is_active', true)
                ->first();

            if (!$emailTemplate) {
                Log::error('=== EMAIL NOT SENT - Template not found ===', [
                    'incident_id' => $incident->id,
                    'template_key' => 'accident_completed',
                    'message' => 'Run the SQL to create the email template'
                ]);
                return;
            }

            Log::info('Email template found', [
                'incident_id' => $incident->id,
                'template_key' => $emailTemplate->key,
                'template_name' => $emailTemplate->name
            ]);

            // Load necessary relationships for PDF
            $incident->load([
                'incidentType',
                'treatmentType',
                'mechanism',
                'injuryType',
                'agency',
                'branch',
                'site.branch',
                'affectedEmployee.site.branch',
                'affectedCustomer',
                'reportedEmployee.site.branch',
                'reportedCustomer',
                'coordinator',
                'creator',
                'updater',
                'correctiveActions.user',
                'comments.user'
            ]);

            Log::info('Generating PDF', ['incident_id' => $incident->id]);

            // Generate PDF
            $pdf = Pdf::loadView('incidents.reports.investigation-pdf', ['incident' => $incident]);
            $pdf->setPaper('A4', 'portrait');

            // Save PDF to temporary location
            $filename = 'incident-investigation-report-' . str_pad($incident->id, 6, '0', STR_PAD_LEFT) . '.pdf';
            $pdfPath = storage_path('app/temp/' . $filename);

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($pdfPath);

            Log::info('PDF saved', [
                'incident_id' => $incident->id,
                'path' => $pdfPath,
                'exists' => file_exists($pdfPath),
                'size' => file_exists($pdfPath) ? filesize($pdfPath) : 0
            ]);

            // Send email
            $recipients = ['sheq@cis-security.co.uk'];

            // Add creator's email if they exist and have an email
            if ($incident->creator && $incident->creator->email) {
                $recipients[] = $incident->creator->email;
            }

            Log::info('Attempting to send email', [
                'incident_id' => $incident->id,
                'recipients' => $recipients
            ]);

            Mail::to($recipients)
                ->send(new AccidentCompletedMail($incident, $emailTemplate, $pdfPath));

            // Clean up temporary PDF file
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            Log::info('=== EMAIL SENT SUCCESSFULLY ===', [
                'incident_id' => $incident->id,
                'recipients' => $recipients,
                'subject' => 'Accident #' . str_pad($incident->id, 6, '0', STR_PAD_LEFT) . ' Completed'
            ]);

        } catch (\Exception $e) {
            Log::error('=== EMAIL FAILED ===', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Assign coordinator to incident
     */
    public function assign(Request $request, IncidentReport $incident)
    {
        if ($incident->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot assign coordinator to archived incidents.'
            ], 400);
        }

        $request->validate([
            'coordinator_id' => 'nullable|exists:users,id'
        ]);

        try {
            $oldCoordinatorId = $incident->coordinator_id;

            $incident->update([
                'coordinator_id' => $request->coordinator_id,
                'updated_by' => Auth::id(),
            ]);

            // Load the coordinator relationship for the response
            $user = $request->coordinator_id ? $incident->coordinator : null;

            // Send email notification to newly assigned coordinator
            if ($request->coordinator_id && $oldCoordinatorId !== $request->coordinator_id) {
                try {
                    // Load necessary relationships for the email
                    $incident->load([
                        'incidentType',
                        'site.branch',
                        'coordinator'
                    ]);

                    // Send email to the newly assigned coordinator
                    if ($user && $user->email) {
                        Mail::to($user->email)
                            ->send(new CoordinatorAssignedMail($incident, $user));

                        Log::info('Coordinator assignment email sent', [
                            'incident_id' => $incident->id,
                            'coordinator_id' => $user->id,
                            'coordinator_email' => $user->email
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send coordinator assignment email', [
                        'incident_id' => $incident->id,
                        'coordinator_id' => $request->coordinator_id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the assignment if email fails
                }
            }

            return response()->json([
                'success' => true,
                'message' => $request->coordinator_id ? 'Coordinator assigned successfully' : 'Coordinator unassigned successfully',
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign coordinator: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archive incident
     */
    public function archive(Request $request, IncidentReport $incident)
    {
        if ($incident->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Incident is already archived.'
            ], 400);
        }

        try {
            $incident->update([
                'archived' => true,
                'archived_by' => Auth::id(),
                'archived_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Incident archived successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive incident: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unarchive incident
     */
    public function unarchive(Request $request, IncidentReport $incident)
    {
        if (!$incident->archived) {
            return response()->json([
                'success' => false,
                'message' => 'Incident is not archived.'
            ], 400);
        }

        try {
            $incident->update([
                'archived' => false,
                'archived_by' => null,
                'archived_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Incident unarchived successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unarchive incident: ' . $e->getMessage()
            ], 500);
        }
    }
}

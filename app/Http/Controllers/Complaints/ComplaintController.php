<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintLog;
use App\Models\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Core Complaint CRUD Controller
 *
 * Handles basic complaint operations: create, store, edit, update, archive, and unarchive.
 */
class ComplaintController extends Controller
{
    /**
     * Display the complaint submission form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $sites = Site::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('complaints.create', compact('sites', 'users'));
    }

    /**
     * Process and store a new complaint submission.
     *
     * Validates the incoming request data against business rules,
     * creates a new complaint record, and provides user feedback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Base validation rules
        $validator = Validator::make($request->all(), [
            'date_received' => 'required|date',
            'name' => 'required|string|max:255',
            'pcn_number' => 'required|string|max:255',
            'site_id' => 'required|exists:sites,id',
            'nature' => 'required|string',
            'date_acknowledged' => 'nullable|date',
            'status' => 'nullable|string|in:open,pending,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'conclusion' => 'nullable|string',
            'date_concluded' => 'nullable|date',
            'ico_complaint' => 'nullable|string',
        ]);

        // Cross-field date constraints
        $validator->after(function ($v) use ($request) {
            $received = $request->input('date_received') ? Carbon::parse($request->input('date_received')) : null;
            $ack = $request->input('date_acknowledged') ? Carbon::parse($request->input('date_acknowledged')) : null;
            $concluded = $request->input('date_concluded') ? Carbon::parse($request->input('date_concluded')) : null;

            if ($ack && $received && $ack->lt($received)) {
                $v->errors()->add('date_acknowledged', 'Date acknowledged cannot be before date received.');
            }

            if ($concluded) {
                if ($ack) {
                    if ($concluded->lt($ack)) {
                        $v->errors()->add('date_concluded', 'Date concluded cannot be before date acknowledged.');
                    }
                } else {
                    $v->errors()->add('date_acknowledged', 'You must provide a date acknowledged before providing a date concluded.');
                }
            }
        });

        $validated = $validator->validate();

        // Ensure default status if not provided
        $validated['status'] = $validated['status'] ?? 'open';

        // Create the complaint
        $complaint = Complaint::create([
            'date_received' => $validated['date_received'],
            'name' => $validated['name'],
            'pcn_number' => $validated['pcn_number'],
            'site_id' => $validated['site_id'],
            'nature' => $validated['nature'],
            'date_acknowledged' => $validated['date_acknowledged'] ?? null,
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'conclusion' => $validated['conclusion'] ?? null,
            'date_concluded' => $validated['date_concluded'] ?? null,
            'ico_complaint' => $validated['ico_complaint'] ?? null,
        ]);

        // Log creation
        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action_type' => 'created',
            'changes' => $complaint->toArray(),
        ]);

        return back()->with('success', 'Complaint saved.');
    }

    /**
     * Show the form for editing an existing complaint.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Complaint $complaint)
    {
        if ($complaint->archived) {
            return redirect()->route('complaints.manage')
                ->with('error', 'Archived complaints cannot be edited.');
        }

        $sites = Site::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('complaints.edit', compact('complaint', 'sites', 'users'));
    }

    /**
     * Update an existing complaint record with validated data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Complaint $complaint)
    {
        if ($complaint->archived) {
            return redirect()->route('complaints.manage')
                ->with('error', 'Archived complaints cannot be updated.');
        }

        $validator = Validator::make($request->all(), [
            'date_received' => 'required|date',
            'name' => 'required|string|max:255',
            'pcn_number' => 'required|string|max:255',
            'site_id' => 'required|exists:sites,id',
            'nature' => 'required|string',
            'date_acknowledged' => 'nullable|date',
            'status' => 'required|string|in:open,pending,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'conclusion' => 'nullable|string',
            'date_concluded' => 'nullable|date',
            'ico_complaint' => 'nullable|string',
        ]);

        $validator->after(function ($v) use ($request) {
            $received = $request->input('date_received') ? Carbon::parse($request->input('date_received')) : null;
            $ack = $request->input('date_acknowledged') ? Carbon::parse($request->input('date_acknowledged')) : null;
            $concluded = $request->input('date_concluded') ? Carbon::parse($request->input('date_concluded')) : null;

            if ($ack && $received && $ack->lt($received)) {
                $v->errors()->add('date_acknowledged', 'Date acknowledged cannot be before date received.');
            }

            if ($concluded) {
                if ($ack) {
                    if ($concluded->lt($ack)) {
                        $v->errors()->add('date_concluded', 'Date concluded cannot be before date acknowledged.');
                    }
                } else {
                    $v->errors()->add('date_acknowledged', 'You must provide a date acknowledged before providing a date concluded.');
                }
            }
        });

        $validated = $validator->validate();

        $changes = [];
        foreach ($validated as $key => $value) {
            if ($complaint->$key != $value) {
                $changes[$key] = [
                    'from' => $complaint->$key,
                    'to' => $value
                ];
            }
        }

        if (!empty($changes)) {
            ComplaintLog::create([
                'complaint_id' => $complaint->id,
                'user_id' => Auth::id(),
                'action_type' => 'updated',
                'changes' => $changes
            ]);
        }

        $complaint->update([
            'date_received' => $validated['date_received'],
            'name' => $validated['name'],
            'pcn_number' => $validated['pcn_number'],
            'site_id' => $validated['site_id'],
            'nature' => $validated['nature'],
            'date_acknowledged' => $validated['date_acknowledged'] ?? null,
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'conclusion' => $validated['conclusion'] ?? null,
            'date_concluded' => $validated['date_concluded'] ?? null,
            'ico_complaint' => $validated['ico_complaint'] ?? null,
        ]);

        if ($request->input('return_to') === 'investigations') {
            return redirect()->route('complaints.my-investigations')->with('success', 'Complaint updated successfully.');
        }

        return redirect()->route('complaints.manage')->with('success', 'Complaint updated.');
    }

    /**
     * Archive a complaint (soft delete)
     *
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Complaint $complaint)
    {
        if ($complaint->archived) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complaint is already archived.'
                ], 400);
            }
            return redirect()->route('complaints.manage')
                ->with('error', 'Complaint is already archived.');
        }

        $complaint->update([
            'archived' => true,
            'archived_by' => Auth::id(),
            'archived_at' => now()
        ]);

        // Log the archiving
        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action_type' => 'archived',
            'changes' => [
                'archived' => [
                    'from' => false,
                    'to' => true
                ]
            ]
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Complaint archived successfully.'
            ]);
        }

        return redirect()->route('complaints.manage')
            ->with('success', 'Complaint archived successfully.');
    }

    /**
     * Restore a complaint from archive
     *
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function unarchive(Complaint $complaint)
    {
        if (!$complaint->archived) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complaint is not archived.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Complaint is not archived.');
        }

        $complaint->update([
            'archived' => false,
            'archived_by' => null,
            'archived_at' => null
        ]);

        // Log the unarchiving
        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action_type' => 'unarchived',
            'changes' => [
                'archived' => [
                    'from' => true,
                    'to' => false
                ]
            ]
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Complaint restored from archive successfully.'
            ]);
        }

        // Check if we came from my-investigations and redirect accordingly
        $referer = request()->headers->get('referer');
        if ($referer && str_contains($referer, 'my-investigations')) {
            return redirect()->route('complaints.my-investigations')
                ->with('success', 'Complaint restored from archive successfully.');
        }

        return redirect()->route('complaints.manage')
            ->with('success', 'Complaint restored from archive successfully.');
    }
}

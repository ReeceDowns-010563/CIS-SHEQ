<?php

namespace App\Http\Controllers\Incidents;

use App\Http\Controllers\Controller;
use App\Mail\IncidentReportMail;
use App\Models\Agency;
use App\Models\BodyPart;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\IncidentComment;
use App\Models\IncidentReport;
use App\Models\IncidentType;
use App\Models\InjuryType;
use App\Models\Mechanism;
use App\Models\Site;
use App\Models\TreatmentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class IncidentFormController extends Controller
{
    /**
     * Show the form for creating a new incident report.
     */
    public function create()
    {
        $user = auth()->user();

        // Get user's accessible site IDs
        $accessibleSiteIds = $user->getAccessibleSiteIds();

        // Get accessible branches
        $accessibleBranchIds = collect();
        if ($accessibleSiteIds->isNotEmpty()) {
            $accessibleBranchIds = Site::whereIn('id', $accessibleSiteIds)
                ->pluck('branch_id')
                ->unique()
                ->values();
        }

        // Get lookup data
        $incidentTypes   = IncidentType::active()->orderBy('name')->get();
        $customers       = Customer::active()->orderBy('name')->get();
        $treatmentTypes  = TreatmentType::active()->orderBy('name')->get();
        $mechanisms      = Mechanism::active()->orderBy('name')->get();
        $bodyParts       = BodyPart::active()->orderBy('name')->get();
        $injuryTypes     = InjuryType::active()->orderBy('name')->get();
        $agencies        = Agency::active()->orderBy('name')->get();

        // Only SHEQ users for coordinator dropdown
        $sheqUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'SHEQ');
        })->active()->orderBy('name')->get();

        $allUsers = User::active()->orderBy('name')->get();

        // Filter employees, branches, sites by access
        $employees = collect();
        $branches  = collect();
        $sites     = collect();

        if ($accessibleSiteIds->isNotEmpty()) {
            $employees = Employee::whereIn('site_id', $accessibleSiteIds)
                ->active()
                ->with(['site', 'site.branch'])
                ->orderBy('first_name')
                ->get();

            $branches = Branch::whereIn('id', $accessibleBranchIds)
                ->orderBy('branch_name')
                ->get();

            $sites = Site::whereIn('id', $accessibleSiteIds)
                ->active()
                ->with('branch')
                ->orderBy('name')
                ->get();
        }

        return view('incidents.create', [
            'incidentTypes'  => $incidentTypes,
            'employees'      => $employees,
            'customers'      => $customers,
            'treatmentTypes' => $treatmentTypes,
            'mechanisms'     => $mechanisms,
            'bodyParts'      => $bodyParts,
            'injuryTypes'    => $injuryTypes,
            'branches'       => $branches,
            'sites'          => $sites,
            'agencies'       => $agencies,
            'sheqUsers'      => $sheqUsers,
            'allUsers'       => $allUsers,
        ]);
    }

    /**
     * Store a newly created incident report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brief_description' => 'required|string|max:500',
            'incident_type_id'  => 'required',
            'incident_type_other_description' => 'required_if:incident_type_id,other|nullable|string|max:255',
            'location' => 'required|string|max:255',
            'additional_information' => 'nullable|string|max:10000',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',

            // Affected Person
            'affected_person_source' => 'required|in:Employee,Customer,Other',
            'affected_employee_id'   => 'required_if:affected_person_source,Employee|nullable|exists:employees,id',
            'affected_customer_id'   => 'required_if:affected_person_source,Customer|nullable|exists:customers,id',
            'affected_person_other'  => 'required_if:affected_person_source,Other|nullable|string|max:255',

            // Reported By
            'reported_by_source'     => 'required|in:Employee,Customer,Other',
            'reported_employee_id'   => 'required_if:reported_by_source,Employee|nullable|exists:employees,id',
            'reported_customer_id'   => 'required_if:reported_by_source,Customer|nullable|exists:customers,id',
            'reported_by_other'      => 'required_if:reported_by_source,Other|nullable|string|max:255',

            // Incident Details
            'treatment_type_id' => 'required|exists:treatment_types,id',
            'mechanism_id'      => 'nullable|exists:mechanisms,id',
            'physician_details' => 'nullable|string|max:1000',
            'date_of_occurrence' => 'required|date|before_or_equal:today',
            'time_of_occurrence' => 'required',

            // Work Details
            'branch_id' => 'required|exists:branches,id',
            'site_id'   => 'nullable|exists:sites,id',
            'agency_id' => 'nullable|exists:agencies,id',

            // Medical Details
            'body_part_id'     => 'nullable|array',
            'body_part_other'  => 'nullable|string|max:255',
            'injury_type_id'   => 'nullable',
            'injury_type_other'=> 'required_if:injury_type_id,other|nullable|string|max:255',
            'what_happened'    => 'required|string|max:10000',

            // Admin
            'coordinator_id' => 'nullable|exists:users,id',
            'bcc_emails'     => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Normalise “other” logic
            $incidentTypeId = $request->incident_type_id === 'other' ? null : $request->incident_type_id;
            $incidentTypeOtherDescription = $request->incident_type_id === 'other'
                ? $request->incident_type_other_description : null;

            $bodyPartIds = [];
            $bodyPartOther = null;
            if ($request->body_part_id && is_array($request->body_part_id)) {
                foreach ($request->body_part_id as $bp) {
                    if ($bp === 'other') {
                        $bodyPartOther = $request->body_part_other;
                    } elseif ($bp) {
                        $bodyPartIds[] = (int) $bp;
                    }
                }
            }
            $finalBodyPartIds = !empty($bodyPartIds) ? $bodyPartIds : null;

            $injuryTypeId = $request->injury_type_id === 'other' ? null : $request->injury_type_id;
            $injuryTypeOther = $request->injury_type_id === 'other'
                ? $request->injury_type_other : null;

            // File uploads
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('incident-attachments');
                    $attachments[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType(),
                    ];
                }
            }

            // Create incident
            $incident = IncidentReport::create([
                'brief_description' => $request->brief_description,
                'incident_type_id'  => $incidentTypeId,
                'incident_type_other_description' => $incidentTypeOtherDescription,
                'location' => $request->location,
                'additional_information' => $request->additional_information,
                'attachments' => $attachments,
                'affected_person_source' => $request->affected_person_source,
                'affected_employee_id'   => $request->affected_employee_id,
                'affected_customer_id'   => $request->affected_customer_id,
                'affected_person_other'  => $request->affected_person_other,
                'reported_by_source'     => $request->reported_by_source,
                'reported_employee_id'   => $request->reported_employee_id,
                'reported_customer_id'   => $request->reported_customer_id,
                'reported_by_other'      => $request->reported_by_other,
                'treatment_type_id'      => $request->treatment_type_id,
                'mechanism_id'           => $request->mechanism_id,
                'physician_details'      => $request->physician_details,
                'date_of_occurrence'     => $request->date_of_occurrence,
                'time_of_occurrence'     => $request->time_of_occurrence,
                'work_shift'             => $request->work_shift,
                'hours_worked_prior'     => $request->hours_worked_prior,
                'branch_id'              => $request->branch_id,
                'site_id'                => $request->site_id,
                'agency_id'              => $request->agency_id,
                'body_part_id'           => $finalBodyPartIds,
                'body_part_other'        => $bodyPartOther,
                'injury_type_id'         => $injuryTypeId,
                'injury_type_other'      => $injuryTypeOther,
                'what_happened'          => $request->what_happened,
                'coordinator_id'         => $request->coordinator_id,
                'created_by'             => Auth::id(),
                'status'                 => 'open',
            ]);

            // -------------------------
            // EMAIL SECTION
            // -------------------------
            $bccEmails = [];

            if ($request->bcc_emails) {
                $selected = json_decode($request->bcc_emails, true);
                if (is_array($selected)) {
                    $bccEmails = User::whereIn('id', $selected)->pluck('email')->toArray();
                }
            }

            if ($incident->coordinator && $incident->coordinator->email) {
                $bccEmails[] = $incident->coordinator->email;
            }

            if (Auth::user() && Auth::user()->email) {
                $bccEmails[] = Auth::user()->email;
            }

            $bccEmails = array_unique($bccEmails);

            try {
                $mail = Mail::to('sheq@cis-security.co.uk');
                if (!empty($bccEmails)) {
                    $mail->bcc($bccEmails);
                }

                Log::info('Incident email send attempt', [
                    'incident_id' => $incident->id,
                    'to' => 'sheq@cis-security.co.uk',
                    'bcc' => $bccEmails,
                ]);

                $mail->send(new IncidentReportMail($incident, 'created'));
            } catch (\Exception $e) {
                Log::error('Failed sending incident email', [
                    'incident_id' => $incident->id,
                    'error' => $e->getMessage(),
                    'bcc' => $bccEmails,
                ]);
            }

            DB::commit();
            return redirect()->route('incidents.show', $incident)
                ->with('success', 'Incident report created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed creating incident', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['attachments']),
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create incident report. Please try again.');
        }
    }

    /**
     * Show the form for editing.
     */
    public function edit(IncidentReport $incident)
    {
        $user = auth()->user();
        $accessibleSiteIds = $user->getAccessibleSiteIds();

        $accessibleBranchIds = collect();
        if ($accessibleSiteIds->isNotEmpty()) {
            $accessibleBranchIds = Site::whereIn('id', $accessibleSiteIds)
                ->pluck('branch_id')
                ->unique()->values();
        }

        $incidentTypes = IncidentType::active()->orderBy('name')->get();
        $customers     = Customer::active()->orderBy('name')->get();
        $treatmentTypes= TreatmentType::active()->orderBy('name')->get();
        $mechanisms    = Mechanism::active()->orderBy('name')->get();
        $bodyParts     = BodyPart::active()->orderBy('name')->get();
        $injuryTypes   = InjuryType::active()->orderBy('name')->get();
        $agencies      = Agency::active()->orderBy('name')->get();

        $sheqUsers = User::whereHas('role', fn($q) => $q->where('name','SHEQ'))
            ->active()->orderBy('name')->get();
        $allUsers  = User::active()->orderBy('name')->get();

        $employees = collect();
        $branches  = collect();
        $sites     = collect();

        if ($accessibleSiteIds->isNotEmpty()) {
            $employees = Employee::whereIn('site_id',$accessibleSiteIds)
                ->active()->with(['site','site.branch'])->orderBy('first_name')->get();
            $branches = Branch::whereIn('id',$accessibleBranchIds)
                ->orderBy('branch_name')->get();
            $sites = Site::whereIn('id',$accessibleSiteIds)
                ->active()->with('branch')->orderBy('name')->get();
        }

        return view('incidents.edit', [
            'incident'       => $incident,
            'incidentTypes'  => $incidentTypes,
            'employees'      => $employees,
            'customers'      => $customers,
            'treatmentTypes' => $treatmentTypes,
            'mechanisms'     => $mechanisms,
            'bodyParts'      => $bodyParts,
            'injuryTypes'    => $injuryTypes,
            'branches'       => $branches,
            'sites'          => $sites,
            'agencies'       => $agencies,
            'sheqUsers'      => $sheqUsers,
            'allUsers'       => $allUsers,
        ]);
    }

    /**
     * Update existing incident.
     */
    public function update(Request $request, IncidentReport $incident)
    {
        $request->validate([
            'brief_description' => 'required|string|max:500',
            'incident_type_id'  => 'required',
            'incident_type_other_description' => 'required_if:incident_type_id,other|nullable|string|max:255',
            'location' => 'required|string|max:255',
            'additional_information' => 'nullable|string|max:10000',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'affected_person_source' => 'required|in:Employee,Customer,Other',
            'affected_employee_id'   => 'required_if:affected_person_source,Employee|nullable|exists:employees,id',
            'affected_customer_id'   => 'required_if:affected_person_source,Customer|nullable|exists:customers,id',
            'affected_person_other'  => 'required_if:affected_person_source,Other|nullable|string|max:255',
            'reported_by_source'     => 'required|in:Employee,Customer,Other',
            'reported_employee_id'   => 'required_if:reported_by_source,Employee|nullable|exists:employees,id',
            'reported_customer_id'   => 'required_if:reported_by_source,Customer|nullable|exists:customers,id',
            'reported_by_other'      => 'required_if:reported_by_source,Other|nullable|string|max:255',
            'treatment_type_id'      => 'required|exists:treatment_types,id',
            'mechanism_id'           => 'nullable|exists:mechanisms,id',
            'physician_details'      => 'nullable|string|max:1000',
            'date_of_occurrence'     => 'required|date|before_or_equal:today',
            'time_of_occurrence'     => 'required',
            'branch_id'              => 'required|exists:branches,id',
            'site_id'                => 'nullable|exists:sites,id',
            'agency_id'              => 'nullable|exists:agencies,id',
            'body_part_id'           => 'nullable|array',
            'body_part_other'        => 'nullable|string|max:255',
            'injury_type_id'         => 'nullable',
            'injury_type_other'      => 'required_if:injury_type_id,other|nullable|string|max:255',
            'what_happened'          => 'required|string|max:10000',
            'coordinator_id'         => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $incidentTypeId = $request->incident_type_id === 'other' ? null : $request->incident_type_id;
            $incidentTypeOtherDescription = $request->incident_type_id === 'other'
                ? $request->incident_type_other_description : null;

            $bodyPartIds = [];
            $bodyPartOther = null;
            if ($request->body_part_id && is_array($request->body_part_id)) {
                foreach ($request->body_part_id as $bp) {
                    if ($bp === 'other') {
                        $bodyPartOther = $request->body_part_other;
                    } elseif ($bp) {
                        $bodyPartIds[] = (int) $bp;
                    }
                }
            }
            $finalBodyPartIds = !empty($bodyPartIds) ? $bodyPartIds : null;

            $injuryTypeId = $request->injury_type_id === 'other' ? null : $request->injury_type_id;
            $injuryTypeOther = $request->injury_type_id === 'other'
                ? $request->injury_type_other : null;

            $existingAttachments = $incident->attachments ?? [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('incident-attachments');
                    $existingAttachments[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
            }

            // Update the incident report
            $incident->update([
                // Basic Information
                'brief_description' => $request->brief_description,
                'incident_type_id' => $incidentTypeId,
                'incident_type_other_description' => $incidentTypeOtherDescription,
                'location' => $request->location,
                'additional_information' => $request->additional_information,
                'attachments' => $existingAttachments,

                // Affected Person
                'affected_person_source' => $request->affected_person_source,
                'affected_employee_id' => $request->affected_employee_id,
                'affected_customer_id' => $request->affected_customer_id,
                'affected_person_other' => $request->affected_person_other,

                // Reported By
                'reported_by_source' => $request->reported_by_source,
                'reported_employee_id' => $request->reported_employee_id,
                'reported_customer_id' => $request->reported_customer_id,
                'reported_by_other' => $request->reported_by_other,

                // Incident Details
                'treatment_type_id' => $request->treatment_type_id,
                'mechanism_id' => $request->mechanism_id,
                'physician_details' => $request->physician_details,
                'date_of_occurrence' => $request->date_of_occurrence,
                'time_of_occurrence' => $request->time_of_occurrence,
                'work_shift' => $request->work_shift,
                'hours_worked_prior' => $request->hours_worked_prior,

                // Work Details
                'branch_id' => $request->branch_id,
                'site_id' => $request->site_id,
                'agency_id' => $request->agency_id,

                // Medical & Incident Specifics - Updated for multi-select
                'body_part_id' => $finalBodyPartIds,
                'body_part_other' => $bodyPartOther,
                'injury_type_id' => $injuryTypeId,
                'injury_type_other' => $injuryTypeOther,
                'what_happened' => $request->what_happened,

                // Administrative
                'coordinator_id' => $request->coordinator_id,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('incidents.show', $incident)
                ->with('success', 'Incident report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update incident report', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update incident report. Please try again.');
        }
    }
}
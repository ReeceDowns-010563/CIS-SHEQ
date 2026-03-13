<?php

namespace App\Http\Controllers\Incidents;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentReportController extends Controller
{
    /**
     * Display the incident investigation report.
     */
    public function show(IncidentReport $incident)
    {
        // Load all necessary relationships with nested relationships
        // Fixed the morphMany relationship name for corrective actions
        $incident->load([
            'incidentType',
            'treatmentType',
            'mechanism',
            // Removed 'bodyPart' - this relationship no longer exists
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
            'correctiveActions.user', // This should load the morphMany relationship
            'comments.user'
        ]);

        // Debug: Check if corrective actions are being loaded
        // dd($incident->correctiveActions);

        return view('incidents.reports.investigation', compact('incident'));
    }

    /**
     * Download the incident investigation report as PDF.
     */
    public function downloadPdf(IncidentReport $incident)
    {
        // Only allow PDF generation for completed or closed incidents
        if (!in_array($incident->status, ['completed', 'closed'])) {
            return redirect()->back()->with('error', 'Investigation reports are only available for completed or closed incidents.');
        }

        // Load all necessary relationships with nested relationships
        $incident->load([
            'incidentType',
            'treatmentType',
            'mechanism',
            // Removed 'bodyPart' - this relationship no longer exists
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

        // Generate PDF using DomPDF with improved options
        $pdf = app('dompdf.wrapper');

        // Set PDF options - disable image processing to avoid GD requirement
        $pdf->getDomPDF()->getOptions()->set('isRemoteEnabled', false);
        $pdf->getDomPDF()->getOptions()->set('defaultFont', 'DejaVu Sans');
        $pdf->getDomPDF()->getOptions()->set('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->getOptions()->set('isPhpEnabled', true);

        $html = view('incidents.reports.investigation-pdf', compact('incident'))->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'incident-investigation-report-' . str_pad($incident->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}

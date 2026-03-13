<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EmailAuditLog;
use App\Services\EmailAuditService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EmailAuditController extends Controller
{
    public function __construct(
        private EmailAuditService $auditService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $logs = $this->auditService->getFilteredAuditLogs($request);
        $filterOptions = $this->auditService->getFilterOptions();

        return view('settings.emails.audit.index', compact('logs', 'filterOptions'));
    }

    public function show(EmailAuditLog $emailAuditLog): View
    {
        $emailAuditLog->load(['user', 'template']);

        return view('settings.emails.audit.show', compact('emailAuditLog'));
    }

    public function export(Request $request): Response
    {
        $logs = $this->auditService->getFilteredAuditLogs($request)
            ->getCollection();

        $csv = $this->auditService->exportToCsv($logs);
        $filename = 'email-audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}

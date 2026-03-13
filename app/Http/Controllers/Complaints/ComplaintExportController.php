<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Complaint Export Controller
 *
 * Handles complaint data export functionality including form display
 * and CSV generation with flexible filtering options.
 */
class ComplaintExportController extends Controller
{
    /**
     * Display the complaint export options interface.
     *
     * Presents users with options for exporting complaint data
     * for reporting and analysis purposes.
     *
     * @return \Illuminate\View\View
     */
    public function form()
    {
        $sites = Site::orderBy('name')->get();
        return view('complaints.download', compact('sites'));
    }

    /**
     * Generate and stream a CSV export of complaint records.
     *
     * Filters complaints based on sites, date range, status, archived flag
     * and formats the data for download as a properly formatted CSV file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'site_ids' => ['sometimes', 'array'],
            'site_ids.*' => ['integer', 'exists:sites,id'],
            'site_id' => ['sometimes', 'integer', 'exists:sites,id'], // backward compatibility
            'date_received_from' => ['nullable', 'date'],
            'date_received_to' => ['nullable', 'date'],
            'status' => ['nullable', 'in:open,pending,closed'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'archived' => ['nullable', 'boolean'],
            'format' => ['nullable', 'in:csv'],
        ]);

        $query = $this->buildExportQuery($validated);
        $data = $this->transformExportData($query->get());
        $filename = $this->generateExportFilename($validated);

        return $this->streamCsvResponse($data, $filename);
    }

    /**
     * Build the export query based on validated filters
     *
     * @param array $validated
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildExportQuery(array $validated)
    {
        // Choose active vs archived set
        if (!empty($validated['archived']) && (bool)$validated['archived']) {
            $query = Complaint::query()->archived()->with(['site', 'assignedTo']);
        } else {
            $query = Complaint::query()->active()->with(['site', 'assignedTo']);
        }

        // Normalize site selection (multi or single)
        $siteIds = $this->normalizeSiteSelection($validated);
        if (count($siteIds)) {
            $query->whereIn('site_id', $siteIds);
        }

        // Date range filtering
        $this->applyDateRangeFilters($query, $validated);

        // Status filter
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        // Assigned user filter
        if (!empty($validated['assigned_to'])) {
            $query->where('assigned_to', $validated['assigned_to']);
        }

        return $query;
    }

    /**
     * Normalize site selection from multiple possible input formats
     *
     * @param array $validated
     * @return array
     */
    private function normalizeSiteSelection(array $validated): array
    {
        $siteIds = [];

        if (!empty($validated['site_ids'])) {
            $siteIds = $validated['site_ids'];
        } elseif (!empty($validated['site_id'])) {
            $siteIds = [$validated['site_id']];
        }

        return $siteIds;
    }

    /**
     * Apply date range filters to the query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $validated
     */
    private function applyDateRangeFilters($query, array $validated): void
    {
        $from = $validated['date_received_from'] ? Carbon::parse($validated['date_received_from']) : null;
        $to = $validated['date_received_to'] ? Carbon::parse($validated['date_received_to']) : null;

        // Ensure logical date order
        if ($from && $to && $from->greaterThan($to)) {
            [$from, $to] = [$to, $from];
        }

        if ($from) {
            $query->whereDate('date_received', '>=', $from->startOfDay());
        }
        if ($to) {
            $query->whereDate('date_received', '<=', $to->endOfDay());
        }
    }

    /**
     * Transform complaint data for export
     *
     * @param \Illuminate\Database\Eloquent\Collection $complaints
     * @return \Illuminate\Support\Collection
     */
    private function transformExportData($complaints)
    {
        return $complaints->map(function ($item) {
            return [
                'Date Complaint Received' => $item->date_received,
                'Name of Complainant' => $item->name,
                'PCN Number' => $item->pcn_number,
                'Site' => $item->site ? $item->site->name : 'N/A',
                'Nature of Complaint' => $item->nature,
                'Date Complaint Acknowledged' => $item->date_acknowledged,
                'Status' => ucfirst($item->status),
                'Assigned To' => $item->assignedTo ? $item->assignedTo->name : 'Unassigned',
                'Conclusion' => $item->conclusion,
                'Date Concluded' => $item->date_concluded,
                'ico Complaint' => $item->ico_complaint,
            ];
        });
    }

    /**
     * Generate descriptive filename for export
     *
     * @param array $validated
     * @return string
     */
    private function generateExportFilename(array $validated): string
    {
        $filenameParts = ['complaints'];
        $siteIds = $this->normalizeSiteSelection($validated);

        // Site information
        if (count($siteIds) === 1) {
            $site = Site::find($siteIds[0]);
            if ($site) {
                $safeName = preg_replace('/[^a-z0-9]+/', '_', strtolower($site->name));
                $filenameParts[] = $safeName;
            }
        } elseif (count($siteIds) > 1) {
            $filenameParts[] = 'multiple_sites';
        } else {
            $filenameParts[] = 'all_sites';
        }

        // Date range information
        $from = $validated['date_received_from'] ? Carbon::parse($validated['date_received_from']) : null;
        $to = $validated['date_received_to'] ? Carbon::parse($validated['date_received_to']) : null;

        if ($from || $to) {
            $range = ($from ? $from->format('Y-m-d') : 'start') . '_to_' . ($to ? $to->format('Y-m-d') : 'end');
            $filenameParts[] = "received_{$range}";
        }

        // Status filter
        if (!empty($validated['status'])) {
            $filenameParts[] = 'status_' . strtolower($validated['status']);
        }

        // Assignment filter
        if (!empty($validated['assigned_to'])) {
            $user = User::find($validated['assigned_to']);
            if ($user) {
                $safeName = preg_replace('/[^a-z0-9]+/', '_', strtolower($user->name));
                $filenameParts[] = 'assigned_' . $safeName;
            }
        }

        // Archive status
        if (!empty($validated['archived']) && (bool)$validated['archived']) {
            $filenameParts[] = 'archived';
        }

        // Current date
        $filenameParts[] = Carbon::now()->format('Y-m-d');

        return implode('_', $filenameParts) . '.csv';
    }

    /**
     * Stream CSV response
     *
     * @param \Illuminate\Support\Collection $data
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function streamCsvResponse($data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            if ($first = $data->first()) {
                fputcsv($handle, array_keys($first));
                foreach ($data as $row) {
                    fputcsv($handle, $row);
                }
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

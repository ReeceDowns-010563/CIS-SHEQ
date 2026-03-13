<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\IncidentReport;
use App\Models\Site;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    /**
     * Show the chart selection page
     */
    public function index()
    {
        return view('charts.index');
    }
    public function complaints(Request $request)
    {
        $chartType = $request->get('chart', 'complaints-trend');
        $timeRange = $request->get('range', '6-months');
        $siteIds = $request->get('sites', []);
        $customFrom = $request->get('custom_from');
        $customTo = $request->get('custom_to');

        // Get available sites for filtering
        $sites = Site::all();

        // Get available chart types for complaints
        $chartTypes = [
            'complaints-trend' => 'Complaint Trends Over Time',
            'complaints-by-type' => 'Complaints by Type',
            'resolution-time' => 'Resolution Time Analysis',
            'complaints-by-site' => 'Complaints by Site',
            'monthly-comparison' => 'Monthly Comparison',
            'status-distribution' => 'Status Distribution'
        ];

        $data = $this->getComplaintsChartData($chartType, $timeRange, $siteIds, $customFrom, $customTo);

        return view('charts.complaints', compact('data', 'chartType', 'timeRange', 'siteIds', 'sites', 'chartTypes', 'customFrom', 'customTo'));
    }

    public function incidents(Request $request)
    {
        $chartType = $request->get('chart', 'incidents-trend');
        $timeRange = $request->get('range', '6-months');
        $siteIds = $request->get('sites', []);
        $customFrom = $request->get('custom_from');
        $customTo = $request->get('custom_to');

        // Get available sites for filtering
        $sites = Site::all();

        // Get available chart types for accidents
        $chartTypes = [
            'incidents-trend' => 'Accident Trends Over Time',
            'incidents-by-type' => 'Accidents by Type',
            'incidents-resolution-time' => 'Resolution Time Analysis',
            'incidents-by-site' => 'Accidents by Site',
            'incidents-monthly-comparison' => 'Monthly Comparison',
            'incidents-status-distribution' => 'Status Distribution'
        ];

        $data = $this->getIncidentsChartData($chartType, $timeRange, $siteIds, $customFrom, $customTo);

        return view('charts.incidents', compact('data', 'chartType', 'timeRange', 'siteIds', 'sites', 'chartTypes', 'customFrom', 'customTo'));
    }

    /**
     * Generate distinct colors for chart segments
     */
    private function generateColors($count)
    {
        $colors = [
            '#FF6384', // Red
            '#36A2EB', // Blue
            '#FFCE56', // Yellow
            '#4BC0C0', // Teal
            '#9966FF', // Purple
            '#FF9F40', // Orange
            '#FF6384', // Pink
            '#C9CBCF', // Grey
            '#4BC0C0', // Cyan
            '#FF6384', // Light Red
            '#36A2EB', // Light Blue
            '#FFCE56', // Light Yellow
            '#4BC0C0', // Light Teal
            '#9966FF', // Light Purple
            '#FF9F40', // Light Orange
            '#90EE90', // Light Green
            '#FFB6C1', // Light Pink
            '#87CEEB', // Sky Blue
            '#DDA0DD', // Plum
            '#F0E68C', // Khaki
        ];

        // If we need more colors than predefined, generate them
        if ($count > count($colors)) {
            $additionalColors = [];
            for ($i = count($colors); $i < $count; $i++) {
                $hue = ($i * 137.508) % 360; // Golden angle approximation
                $saturation = 70 + ($i % 30); // Vary saturation
                $lightness = 50 + ($i % 40); // Vary lightness
                $additionalColors[] = "hsl($hue, {$saturation}%, {$lightness}%)";
            }
            $colors = array_merge($colors, $additionalColors);
        }

        return array_slice($colors, 0, $count);
    }

    /**
     * Generate background colors with transparency
     */
    private function generateBackgroundColors($count, $alpha = 0.8)
    {
        $baseColors = $this->generateColors($count);
        return array_map(function($color) use ($alpha) {
            if (strpos($color, 'hsl') === 0) {
                return $color; // HSL colors already have transparency support
            }
            // Convert hex to rgba
            $hex = str_replace('#', '', $color);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "rgba($r, $g, $b, $alpha)";
        }, $baseColors);
    }

    // COMPLAINTS CHART DATA METHODS
    private function getComplaintsChartData($chartType, $timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        switch ($chartType) {
            case 'complaints-trend':
                return $this->getComplaintsTrendData($timeRange, $siteIds, $customFrom, $customTo);
            case 'complaints-by-type':
                return $this->getComplaintsByTypeData($timeRange, $siteIds, $customFrom, $customTo);
            case 'resolution-time':
                return $this->getComplaintsResolutionTimeData($timeRange, $siteIds, $customFrom, $customTo);
            case 'complaints-by-site':
                return $this->getComplaintsBySiteData($timeRange, $customFrom, $customTo);
            case 'monthly-comparison':
                return $this->getComplaintsMonthlyComparisonData($timeRange, $siteIds, $customFrom, $customTo);
            case 'status-distribution':
                return $this->getComplaintsStatusDistributionData($timeRange, $siteIds, $customFrom, $customTo);
            default:
                return $this->getComplaintsTrendData($timeRange, $siteIds, $customFrom, $customTo);
        }
    }

    // INCIDENTS CHART DATA METHODS
    private function getIncidentsChartData($chartType, $timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        switch ($chartType) {
            case 'incidents-trend':
                return $this->getIncidentsTrendData($timeRange, $siteIds, $customFrom, $customTo);
            case 'incidents-by-type':
                return $this->getIncidentsByTypeData($timeRange, $siteIds, $customFrom, $customTo);
            case 'incidents-resolution-time':
                return $this->getIncidentsResolutionTimeData($timeRange, $siteIds, $customFrom, $customTo);
            case 'incidents-by-site':
                return $this->getIncidentsBySiteData($timeRange, $customFrom, $customTo);
            case 'incidents-monthly-comparison':
                return $this->getIncidentsMonthlyComparisonData($timeRange, $siteIds, $customFrom, $customTo);
            case 'incidents-status-distribution':
                return $this->getIncidentsStatusDistributionData($timeRange, $siteIds, $customFrom, $customTo);
            default:
                return $this->getIncidentsTrendData($timeRange, $siteIds, $customFrom, $customTo);
        }
    }

    private function getDateRange($timeRange, $customFrom = null, $customTo = null)
    {
        if ($timeRange === 'custom' && $customFrom && $customTo) {
            return [
                'start' => Carbon::parse($customFrom),
                'end' => Carbon::parse($customTo),
                'months' => null
            ];
        }

        $months = match($timeRange) {
            '30-days' => null,
            '3-months' => 3,
            '12-months' => 12,
            default => 6
        };

        if ($timeRange === '30-days') {
            return [
                'start' => Carbon::now()->subDays(30),
                'end' => Carbon::now(),
                'months' => null
            ];
        }

        return [
            'start' => Carbon::now()->subMonths($months),
            'end' => Carbon::now(),
            'months' => $months
        ];
    }

    // COMPLAINTS DATA METHODS
    private function getComplaintsTrendData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = Complaint::active();

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        if ($dateRange['months']) {
            $data = collect(range($dateRange['months'] - 1, 0))->map(function ($monthsBack) use ($query) {
                $date = Carbon::now()->subMonths($monthsBack);

                $count = (clone $query)
                    ->whereYear('date_received', $date->year)
                    ->whereMonth('date_received', $date->month)
                    ->count();

                return [
                    'month' => $date->format('M Y'),
                    'count' => $count
                ];
            });
        } else {
            // For 30 days or custom range, group by days or appropriate intervals
            $days = $dateRange['start']->diffInDays($dateRange['end']);
            $interval = $days > 30 ? 'month' : 'day';

            if ($interval === 'day') {
                $data = collect();
                $currentDate = $dateRange['start']->copy();

                while ($currentDate->lte($dateRange['end'])) {
                    $count = (clone $query)
                        ->whereDate('date_received', $currentDate)
                        ->count();

                    $data->push([
                        'month' => $currentDate->format('M d'),
                        'count' => $count
                    ]);

                    $currentDate->addDay();
                }
            } else {
                $data = collect();
                $currentDate = $dateRange['start']->copy()->startOfMonth();

                while ($currentDate->lte($dateRange['end'])) {
                    $count = (clone $query)
                        ->whereYear('date_received', $currentDate->year)
                        ->whereMonth('date_received', $currentDate->month)
                        ->count();

                    $data->push([
                        'month' => $currentDate->format('M Y'),
                        'count' => $count
                    ]);

                    $currentDate->addMonth();
                }
            }
        }

        return [
            'type' => 'line',
            'title' => 'Complaint Trends Over Time',
            'data' => $data,
            'labels' => $data->pluck('month'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors(1),
            'backgroundColors' => $this->generateBackgroundColors(1, 0.2)
        ];
    }

    private function getComplaintsByTypeData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = Complaint::active()
            ->where('date_received', '>=', $dateRange['start'])
            ->where('date_received', '<=', $dateRange['end']);

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        $data = $query
            ->select('nature')
            ->selectRaw('count(*) as count')
            ->groupBy('nature')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $dataCount = $data->count();

        return [
            'type' => 'pie',
            'title' => 'Complaints by Type',
            'data' => $data,
            'labels' => $data->pluck('nature'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.8)
        ];
    }

    private function getComplaintsResolutionTimeData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = Complaint::active()
            ->where('date_received', '>=', $dateRange['start'])
            ->where('date_received', '<=', $dateRange['end'])
            ->whereNotNull('date_concluded')
            ->whereNotNull('date_received');

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        $complaints = $query->get();

        // Group by resolution time ranges for better analysis
        $timeRanges = [
            '0-1 days' => 0,
            '2-3 days' => 0,
            '4-7 days' => 0,
            '8-14 days' => 0,
            '15-30 days' => 0,
            '30+ days' => 0
        ];

        foreach ($complaints as $complaint) {
            try {
                $received = Carbon::parse($complaint->getRawOriginal('date_received'));
                $concluded = Carbon::parse($complaint->getRawOriginal('date_concluded'));
                $days = $received->diffInDays($concluded);

                if ($days <= 1) {
                    $timeRanges['0-1 days']++;
                } elseif ($days <= 3) {
                    $timeRanges['2-3 days']++;
                } elseif ($days <= 7) {
                    $timeRanges['4-7 days']++;
                } elseif ($days <= 14) {
                    $timeRanges['8-14 days']++;
                } elseif ($days <= 30) {
                    $timeRanges['15-30 days']++;
                } else {
                    $timeRanges['30+ days']++;
                }
            } catch (Exception $e) {
                // Skip invalid dates
            }
        }

        $data = collect($timeRanges);
        $dataCount = count($timeRanges);

        return [
            'type' => 'radar',
            'title' => 'Complaints Resolution Time Analysis',
            'data' => $data,
            'labels' => collect($timeRanges)->keys(),
            'values' => collect($timeRanges)->values(),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.3)
        ];
    }

    private function getComplaintsBySiteData($timeRange, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $data = Complaint::active()
            ->where('date_received', '>=', $dateRange['start'])
            ->where('date_received', '<=', $dateRange['end'])
            ->join('sites', 'complaints.site_id', '=', 'sites.id')
            ->select('sites.name as site_name')
            ->selectRaw('count(*) as count')
            ->groupBy('sites.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $dataCount = $data->count();

        return [
            'type' => 'pie',
            'title' => 'Complaints by Site',
            'data' => $data,
            'labels' => $data->pluck('site_name'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.8)
        ];
    }

    private function getComplaintsMonthlyComparisonData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = Complaint::active();
        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        // For custom range, compare with previous period of same length
        if ($timeRange === 'custom') {
            $days = $dateRange['start']->diffInDays($dateRange['end']);
            $previousStart = $dateRange['start']->copy()->subDays($days);
            $previousEnd = $dateRange['start']->copy()->subDay();
        } else {
            $months = $dateRange['months'] ?? 1;
            $previousStart = Carbon::now()->subMonths($months * 2);
            $previousEnd = Carbon::now()->subMonths($months);
        }

        if ($dateRange['months']) {
            // Monthly grouping for standard ranges
            $currentData = collect(range($dateRange['months'] - 1, 0))->map(function ($monthsBack) use ($query) {
                $date = Carbon::now()->subMonths($monthsBack);
                $count = (clone $query)
                    ->whereYear('date_received', $date->year)
                    ->whereMonth('date_received', $date->month)
                    ->count();

                return [
                    'month' => $date->format('M Y'),
                    'current' => $count
                ];
            });

            $previousData = collect(range(($dateRange['months'] * 2) - 1, $dateRange['months']))->map(function ($monthsBack) use ($query) {
                $date = Carbon::now()->subMonths($monthsBack);
                $count = (clone $query)
                    ->whereYear('date_received', $date->year)
                    ->whereMonth('date_received', $date->month)
                    ->count();

                return $count;
            });
        } else {
            // Custom range comparison
            $currentCount = (clone $query)
                ->whereBetween('date_received', [$dateRange['start'], $dateRange['end']])
                ->count();

            $previousCount = (clone $query)
                ->whereBetween('date_received', [$previousStart, $previousEnd])
                ->count();

            $currentData = collect([['month' => 'Current Period', 'current' => $currentCount]]);
            $previousData = collect([$previousCount]);
        }

        $labels = $currentData->pluck('month');
        $currentValues = $currentData->pluck('current');
        $previousValues = $previousData->values();

        return [
            'type' => 'bar',
            'title' => 'Complaints Monthly Comparison - Current vs Previous Period',
            'data' => [
                'current' => $currentValues,
                'previous' => $previousValues
            ],
            'labels' => $labels,
            'values' => $currentValues,
            'datasets' => [
                [
                    'label' => 'Current Period',
                    'data' => $currentValues,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Previous Period',
                    'data' => $previousValues,
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF6384',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    private function getComplaintsStatusDistributionData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = Complaint::active()
            ->where('date_received', '>=', $dateRange['start'])
            ->where('date_received', '<=', $dateRange['end']);

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        $data = $query
            ->select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->status),
                    'count' => $item->count
                ];
            });

        $dataCount = $data->count();

        return [
            'type' => 'doughnut',
            'title' => 'Complaints Status Distribution',
            'data' => $data,
            'labels' => $data->pluck('status'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.8)
        ];
    }

    // INCIDENTS DATA METHODS
    private function getIncidentsTrendData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = IncidentReport::active();

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        if ($dateRange['months']) {
            $data = collect(range($dateRange['months'] - 1, 0))->map(function ($monthsBack) use ($query) {
                $date = Carbon::now()->subMonths($monthsBack);

                $count = (clone $query)
                    ->whereYear('date_of_occurrence', $date->year)
                    ->whereMonth('date_of_occurrence', $date->month)
                    ->count();

                return [
                    'month' => $date->format('M Y'),
                    'count' => $count
                ];
            });
        } else {
            $days = $dateRange['start']->diffInDays($dateRange['end']);
            $interval = $days > 30 ? 'month' : 'day';

            if ($interval === 'day') {
                $data = collect();
                $currentDate = $dateRange['start']->copy();

                while ($currentDate->lte($dateRange['end'])) {
                    $count = (clone $query)
                        ->whereDate('date_of_occurrence', $currentDate)
                        ->count();

                    $data->push([
                        'month' => $currentDate->format('M d'),
                        'count' => $count
                    ]);

                    $currentDate->addDay();
                }
            } else {
                $data = collect();
                $currentDate = $dateRange['start']->copy()->startOfMonth();

                while ($currentDate->lte($dateRange['end'])) {
                    $count = (clone $query)
                        ->whereYear('date_of_occurrence', $currentDate->year)
                        ->whereMonth('date_of_occurrence', $currentDate->month)
                        ->count();

                    $data->push([
                        'month' => $currentDate->format('M Y'),
                        'count' => $count
                    ]);

                    $currentDate->addMonth();
                }
            }
        }

        return [
            'type' => 'line',
            'title' => 'Accident Trends Over Time',
            'data' => $data,
            'labels' => $data->pluck('month'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors(1),
            'backgroundColors' => $this->generateBackgroundColors(1, 0.2)
        ];
    }

    private function getIncidentsByTypeData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = IncidentReport::active()
            ->where('date_of_occurrence', '>=', $dateRange['start'])
            ->where('date_of_occurrence', '<=', $dateRange['end']);

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        // Join with incident_types table to get the type names
        $data = $query
            ->join('incident_types', 'incident_reports.incident_type_id', '=', 'incident_types.id')
            ->select('incident_types.name as type_name')
            ->selectRaw('count(*) as count')
            ->groupBy('incident_types.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $dataCount = $data->count();

        return [
            'type' => 'pie',
            'title' => 'Accidents by Type',
            'data' => $data,
            'labels' => $data->pluck('type_name'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.8)
        ];
    }

    private function getIncidentsResolutionTimeData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        // Note: IncidentReport model doesn't have date_concluded field based on your model
        // We'll skip this analysis for now or you can add the field to your model
        $timeRanges = [
            '0-1 days' => 0,
            '2-3 days' => 0,
            '4-7 days' => 0,
            '8-14 days' => 0,
            '15-30 days' => 0,
            '30+ days' => 0
        ];

        $data = collect($timeRanges);
        $dataCount = count($timeRanges);

        return [
            'type' => 'radar',
            'title' => 'Accidents Resolution Time Analysis (Data not available)',
            'data' => $data,
            'labels' => collect($timeRanges)->keys(),
            'values' => collect($timeRanges)->values(),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.3)
        ];
    }

    private function getIncidentsBySiteData($timeRange, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $data = IncidentReport::active()
            ->where('date_of_occurrence', '>=', $dateRange['start'])
            ->where('date_of_occurrence', '<=', $dateRange['end'])
            ->join('sites', 'incident_reports.site_id', '=', 'sites.id')
            ->select('sites.name as site_name')
            ->selectRaw('count(*) as count')
            ->groupBy('sites.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $dataCount = $data->count();

        return [
            'type' => 'pie',
            'title' => 'Accidents by Site',
            'data' => $data,
            'labels' => $data->pluck('site_name'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.8)
        ];
    }

    private function getIncidentsMonthlyComparisonData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = IncidentReport::active();
        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        if ($timeRange === 'custom') {
            $days = $dateRange['start']->diffInDays($dateRange['end']);
            $previousStart = $dateRange['start']->copy()->subDays($days);
            $previousEnd = $dateRange['start']->copy()->subDay();
        } else {
            $months = $dateRange['months'] ?? 1;
            $previousStart = Carbon::now()->subMonths($months * 2);
            $previousEnd = Carbon::now()->subMonths($months);
        }

        if ($dateRange['months']) {
            $currentData = collect(range($dateRange['months'] - 1, 0))->map(function ($monthsBack) use ($query) {
                $date = Carbon::now()->subMonths($monthsBack);
                $count = (clone $query)
                    ->whereYear('date_of_occurrence', $date->year)
                    ->whereMonth('date_of_occurrence', $date->month)
                    ->count();

                return [
                    'month' => $date->format('M Y'),
                    'current' => $count
                ];
            });

            $previousData = collect(range(($dateRange['months'] * 2) - 1, $dateRange['months']))->map(function ($monthsBack) use ($query) {
                $date = Carbon::now()->subMonths($monthsBack);
                $count = (clone $query)
                    ->whereYear('date_of_occurrence', $date->year)
                    ->whereMonth('date_of_occurrence', $date->month)
                    ->count();

                return $count;
            });
        } else {
            $currentCount = (clone $query)
                ->whereBetween('date_of_occurrence', [$dateRange['start'], $dateRange['end']])
                ->count();

            $previousCount = (clone $query)
                ->whereBetween('date_of_occurrence', [$previousStart, $previousEnd])
                ->count();

            $currentData = collect([['month' => 'Current Period', 'current' => $currentCount]]);
            $previousData = collect([$previousCount]);
        }

        $labels = $currentData->pluck('month');
        $currentValues = $currentData->pluck('current');
        $previousValues = $previousData->values();

        return [
            'type' => 'bar',
            'title' => 'Accidents Monthly Comparison - Current vs Previous Period',
            'data' => [
                'current' => $currentValues,
                'previous' => $previousValues
            ],
            'labels' => $labels,
            'values' => $currentValues,
            'datasets' => [
                [
                    'label' => 'Current Period',
                    'data' => $currentValues,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Previous Period',
                    'data' => $previousValues,
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF6384',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    private function getIncidentsStatusDistributionData($timeRange, $siteIds = null, $customFrom = null, $customTo = null)
    {
        $dateRange = $this->getDateRange($timeRange, $customFrom, $customTo);

        $query = IncidentReport::active()
            ->where('date_of_occurrence', '>=', $dateRange['start'])
            ->where('date_of_occurrence', '<=', $dateRange['end']);

        if ($siteIds && is_array($siteIds) && count($siteIds) > 0) {
            $query->whereIn('site_id', $siteIds);
        }

        $data = $query
            ->select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->status),
                    'count' => $item->count
                ];
            });

        $dataCount = $data->count();

        return [
            'type' => 'doughnut',
            'title' => 'Accidents Status Distribution',
            'data' => $data,
            'labels' => $data->pluck('status'),
            'values' => $data->pluck('count'),
            'colors' => $this->generateColors($dataCount),
            'backgroundColors' => $this->generateBackgroundColors($dataCount, 0.8)
        ];
    }
}

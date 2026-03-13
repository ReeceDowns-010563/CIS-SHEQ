<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller responsible for documentation-related UI, particularly the listing
 * and filtering of guides created by the current user.
 */
class DocumentationController extends Controller
{
    /**
     * Show the documentation landing page.
     */
    public function index(): View
    {
        return view('settings.documentation.index');
    }

    /**
     * List the user's guides with search, visibility filtering, and sorting.
     *
     * Supports:
     *  - Text search against title and description.
     *  - Visibility filtering (public / private).
     *  - Sorting by allowed fields with direction.
     *  - Pagination with query string preservation.
     */
    public function guides(Request $request): View
    {
        $userId = auth()->id();

        $query = Guide::where('created_by', $userId);

        // Full-text-ish search on title/description if provided.
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Visibility filter: only apply if the provided value is one of the expected.
        $visibility = $request->input('visibility');
        if ($visibility === 'public') {
            $query->where('is_public', true);
        } elseif ($visibility === 'private') {
            $query->where('is_public', false);
        }

        // Sorting: whitelist fields and ensure direction is valid.
        $allowedSorts = ['title', 'created_at'];
        $sort = in_array($request->input('sort'), $allowedSorts, true)
            ? $request->input('sort')
            : 'created_at';

        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        // Paginate and keep existing query string parameters for links.
        $guides = $query->paginate(15)->withQueryString();

        return view('settings.documentation.guides.index', compact('guides'));
    }
}

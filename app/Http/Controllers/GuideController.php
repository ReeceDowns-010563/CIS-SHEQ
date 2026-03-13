<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles CRUD and access control for guides (PDF documentation), including
 * creation, visibility management, share links, and secure streaming.
 */
class GuideController extends Controller
{
    /**
     * List the current user's guides with search, visibility filtering, sorting and pagination.
     */
    public function index(Request $request): View
    {
        $query = Guide::where('created_by', auth()->id());

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $visibility = $request->input('visibility');
        if ($visibility === 'public') {
            $query->where('is_public', true);
        } elseif ($visibility === 'private') {
            $query->where('is_public', false);
        }

        $allowedSorts = ['title', 'created_at'];
        $sort = in_array($request->input('sort'), $allowedSorts, true)
            ? $request->input('sort')
            : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        $guides = $query->paginate(15)->withQueryString();

        return view('settings.documentation.guides.index', compact('guides'));
    }

    /**
     * Show form to create a new guide.
     */
    public function create(): View
    {
        return view('settings.documentation.guides.create');
    }

    /**
     * Validate input, store uploaded PDF, and persist a new guide record.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['sometimes', 'boolean'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        // Persist uploaded file to storage.
        $path = $request->file('file')->store('guides');

        $guide = new Guide([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_public' => $request->boolean('is_public'),
            'file_path' => $path,
        ]);

        // Associate creator and save.
        $guide->created_by = auth()->id();
        $guide->save();

        return redirect()->route('settings.documentation.guides.index')
            ->with('success', 'Guide created.');
    }

    /**
     * Show edit form for an existing guide. Authorization enforced via policy.
     */
    public function edit(Guide $guide): View
    {
        $this->authorize('update', $guide);
        return view('settings.documentation.guides.edit', compact('guide'));
    }

    /**
     * Apply updates to a guide including optional file replacement and share token handling.
     */
    public function update(Request $request, Guide $guide): RedirectResponse
    {
        $this->authorize('update', $guide);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['sometimes', 'boolean'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $becamePublic = ! $guide->is_public && $request->boolean('is_public');

        // Replace file if a new one is uploaded.
        if ($request->hasFile('file')) {
            Storage::delete($guide->file_path);
            $guide->file_path = $request->file('file')->store('guides');
        }

        $guide->title = $data['title'];
        $guide->description = $data['description'] ?? null;
        $guide->is_public = $request->boolean('is_public');

        // If transitioning from private to public, invalidate existing share link.
        if ($becamePublic && $guide->share_token) {
            $guide->revokeShareToken(); // Assumes method persists its change.
        }

        $guide->save();

        $message = 'Guide updated.';
        if ($becamePublic) {
            $message .= ' Share token revoked because guide is now public.';
        }

        return $becamePublic
            ? redirect()->route('settings.documentation.guides.index')->with('success', $message)
            : back()->with('success', $message);
    }

    /**
     * Delete a guide and its underlying file. Authorization enforced via policy.
     */
    public function destroy(Guide $guide): RedirectResponse
    {
        $this->authorize('delete', $guide);
        Storage::delete($guide->file_path);
        $guide->delete();

        return redirect()->route('settings.documentation.guides.index')->with('success', 'Deleted.');
    }

    /**
     * Stream a guide by UUID. Public guides are accessible to anyone; private guides
     * require authentication. (Ownership/extra authorisation is not enforced here.)
     */
    public function show(string $uuid): Response
    {
        $guide = Guide::where('uuid', $uuid)->firstOrFail();

        if ($guide->is_public) {
            return $this->streamPdf($guide);
        }

        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        return $this->streamPdf($guide);
    }

    /**
     * Stream a guide via a share token. Validates token and applies auth requirement
     * for private guides.
     */
    public function shared(string $uuid, string $token): Response
    {
        $guide = Guide::where('uuid', $uuid)->firstOrFail();

        if (! $guide->share_token || ! hash_equals($guide->share_token, $token)) {
            abort(404);
        }

        if (! $guide->is_public && ! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        return $this->streamPdf($guide);
    }

    /**
     * Internal helper to return the PDF file response with appropriate headers.
     */
    protected function streamPdf(Guide $guide): Response
    {
        return Storage::response($guide->file_path, null, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Regenerate the share token for a guide. Authorization enforced via policy.
     */
    public function regenerateShareToken(Guide $guide): RedirectResponse
    {
        $this->authorize('update', $guide);
        $guide->generateShareToken();
        return back()->with('success', 'Share link generated.');
    }

    /**
     * Revoke the current share token. Authorization enforced via policy.
     */
    public function revokeShareToken(Guide $guide): RedirectResponse
    {
        $this->authorize('update', $guide);
        $guide->revokeShareToken();
        return back()->with('success', 'Share link revoked.');
    }
}

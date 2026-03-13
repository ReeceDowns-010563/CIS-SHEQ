<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Manages the dashboard API token for the current user.
 * Provides viewing/creation and regeneration of a single named token.
 */
class ApiSettingsController extends Controller
{
    // Consistent token name used for dashboard access.
    private const TOKEN_NAME = 'dashboard-token';

    /**
     * Show the API settings page, creating a token if one does not already exist.
     *
     * If a token already exists, we do not re-expose its plaintext value
     * (since Sanctum only returns the raw token on creation), but we indicate
     * to the view that a token has been generated previously.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Check for an existing token by name.
        $existingToken = $user->tokens()->where('name', self::TOKEN_NAME)->first();

        $newToken = null;
        $alreadyGenerated = false;

        if (! $existingToken) {
            // No existing token: create and expose the plaintext value once.
            $newToken = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        } else {
            // A token was previously created; do not re-display its value.
            $alreadyGenerated = true;
        }

        return view('settings.documentation.API-Settings.index', [
            'user' => $user,
            'token' => $newToken,
            'alreadyGenerated' => $alreadyGenerated,
        ]);
    }

    /**
     * Revoke any existing dashboard token(s) and create a fresh one.
     * Returns to the settings page with the new token exposed.
     */
    public function regenerateToken(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Remove all existing tokens with the dashboard name.
        $user->tokens()->where('name', self::TOKEN_NAME)->delete();

        // Issue a new token and expose it once via flash data.
        $newToken = $user->createToken(self::TOKEN_NAME)->plainTextToken;

        return redirect()
            ->route('settings.api.settings.index')
            ->with('token', $newToken)
            ->with('regenerated', true);
    }
}

<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        $query = EmailTemplate::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('key', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $templates = $query->orderBy('name')->paginate(15);

        // Get available categories for filter
        $categories = EmailTemplate::distinct('category')
            ->whereNotNull('category')
            ->pluck('category')
            ->sort();

        return view('settings.emails.templates.index', compact('templates', 'search', 'category', 'categories'));
    }

    public function create()
    {
        return view('settings.emails.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name',
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'variables' => 'nullable|array',
            'sample_data' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $template = EmailTemplate::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'variables' => $request->variables ?? [],
            'sample_data' => $request->sample_data ?? [],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('settings.emails.templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function show(EmailTemplate $emailTemplate)
    {
        return view('settings.emails.templates.show', compact('emailTemplate'));
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('settings.emails.templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $emailTemplate->id,
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'variables' => 'nullable|array',
            'sample_data' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $emailTemplate->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'variables' => $request->variables ?? [],
            'sample_data' => $request->sample_data ?? [],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('settings.emails.templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function preview(Request $request, EmailTemplate $emailTemplate): JsonResponse
    {
        // Modes: 'placeholders' (raw) or 'sample' (render with sample data)
        $mode = $request->get('mode', 'placeholders');

        $subject = $emailTemplate->subject;
        $body    = $emailTemplate->body_html;

        if ($mode === 'sample') {
            $sampleData = $emailTemplate->getSampleData();

            if (!empty($sampleData)) {
                foreach ($sampleData as $variable => $value) {
                    // Changed to replace {variable} with optional whitespace instead of {{variable}}
                    $pattern = '/\{\s*' . preg_quote($variable, '/') . '\s*\}/';
                    $subject = preg_replace($pattern, (string) $value, $subject);
                    $body    = preg_replace($pattern, (string) $value, $body);
                }
            }
        }

        return response()->json([
            'success'         => true,
            'mode'            => $mode,
            'subject'         => $subject,
            'body'            => $body, // Keep HTML intact for innerHTML
            'has_sample_data' => !empty($emailTemplate->getSampleData()),
            'variables'       => $emailTemplate->getVariablesList(),
        ]);
    }

    public function sendTest(Request $request, EmailTemplate $emailTemplate): JsonResponse
    {
        $request->validate([
            'recipient_email' => 'required|email',
            'variables' => 'nullable|array',
        ]);

        try {
            $variables = $request->input('variables', []);
            $recipientEmail = $request->input('recipient_email');

            // Render the template with variables
            $renderedData = $emailTemplate->renderWithSampleData($variables);

            // Send email using Mail::html() directly - no Mailable class needed
            Mail::html(
                $renderedData['body'],
                function ($message) use ($renderedData, $recipientEmail, $emailTemplate) {
                    $message->to($recipientEmail)
                        ->subject($renderedData['subject'])
                        ->from(config('mail.from.address'), config('mail.from.name'));
                }
            );

            Log::info('Test email sent successfully', [
                'template_id' => $emailTemplate->id,
                'template_name' => $emailTemplate->name,
                'recipient' => $recipientEmail,
                'variables' => $variables
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $recipientEmail
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'template_id' => $emailTemplate->id,
                'recipient' => $request->input('recipient_email'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('settings.emails.templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}

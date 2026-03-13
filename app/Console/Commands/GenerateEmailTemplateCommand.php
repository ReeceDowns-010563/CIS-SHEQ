<?php

namespace App\Console\Commands;

use App\Models\EmailTemplate;
use Illuminate\Console\Command;

class GenerateEmailTemplateCommand extends Command
{
    protected $signature = 'make:email-template {key} {name} {--category=general} {--description=}';
    protected $description = 'Generate a new email template';

    public function handle(): int
    {
        $key = $this->argument('key');
        $name = $this->argument('name');
        $category = $this->option('category');
        $description = $this->option('description');

        if (EmailTemplate::where('key', $key)->exists()) {
            $this->error("Email template with key '{$key}' already exists.");
            return self::FAILURE;
        }

        $subject = $this->ask('Enter the email subject');
        $bodyHtml = $this->ask('Enter the HTML body content');

        $template = EmailTemplate::create([
            'key' => $key,
            'name' => $name,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'category' => $category,
            'description' => $description,
            'variables' => [],
            'sample_data' => [],
        ]);

        $this->info("Email template '{$name}' created successfully with ID: {$template->id}");
        $this->line("Template key: {$key}");

        return self::SUCCESS;
    }
}

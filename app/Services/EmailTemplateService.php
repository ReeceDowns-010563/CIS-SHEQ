<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EmailTemplateService
{
    private const SENSITIVE_FIELDS = [
        'password', 'token', 'secret', 'key', 'dob', 'date_of_birth',
        'ssn', 'social_security', 'credit_card', 'bank_account',
        'api_key', 'private_key', 'access_token', 'refresh_token'
    ];

    public function getAllTemplates(): Collection
    {
        return EmailTemplate::orderBy('category')->orderBy('name')->get();
    }

    public function searchTemplates(string $search = null, string $category = null): Collection
    {
        $query = EmailTemplate::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('key', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->byCategory($category);
        }

        return $query->orderBy('category')->orderBy('name')->get();
    }

    public function getCategories(): array
    {
        return EmailTemplate::distinct('category')
            ->pluck('category')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }

    public function canUserSendTest(int $userId, int $hourlyLimit = 10): bool
    {
        $cacheKey = "email_test_limit_{$userId}_" . now()->format('Y-m-d-H');
        $currentCount = Cache::get($cacheKey, 0);

        return $currentCount < $hourlyLimit;
    }

    public function incrementTestSendLimit(int $userId): void
    {
        $cacheKey = "email_test_limit_{$userId}_" . now()->format('Y-m-d-H');
        $currentCount = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $currentCount + 1, now()->addHour());
    }

    public function redactSensitiveData(array $variables): array
    {
        $redacted = [];

        foreach ($variables as $key => $value) {
            if ($this->isSensitiveField($key)) {
                $redacted[$key] = $this->maskValue($value);
            } else {
                $redacted[$key] = $value;
            }
        }

        return $redacted;
    }

    private function isSensitiveField(string $fieldName): bool
    {
        $fieldName = strtolower($fieldName);

        foreach (self::SENSITIVE_FIELDS as $sensitiveField) {
            if (str_contains($fieldName, $sensitiveField)) {
                return true;
            }
        }

        return false;
    }

    private function maskValue($value): string
    {
        if (is_string($value) && strlen($value) > 0) {
            if (strlen($value) <= 4) {
                return str_repeat('*', strlen($value));
            }
            return substr($value, 0, 2) . str_repeat('*', strlen($value) - 4) . substr($value, -2);
        }

        return '***';
    }
}

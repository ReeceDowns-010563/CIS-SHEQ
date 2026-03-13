<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'subject',
        'body_html',
        'category',
        'description',
        'is_active',
        'sample_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sample_data' => 'array',
    ];

    /**
     * Get a list of unique variables (placeholders) from the template's subject and body.
     * This method dynamically finds variables, so you don't need a 'variables' column in your database.
     *
     * @return array
     */
    public function getVariablesList(): array
    {
        $content = $this->subject . ' ' . $this->body_html;
        // Changed to look for single curly braces {variable} instead of double {{variable}}
        preg_match_all('/\{\s*([a-zA-Z0-9_]+)\s*\}/', $content, $matches);

        return array_unique($matches[1]);
    }

    /**
     * Get the sample data value for a specific variable, returning an empty string if not found.
     *
     * @param string $variable
     * @return string
     */
    public function getSampleDataForVariable(string $variable): string
    {
        return $this->sample_data[$variable] ?? '';
    }

    /**
     * Get the full array of sample data.
     *
     * @return array
     */
    public function getSampleData(): array
    {
        return $this->sample_data ?? [];
    }

    /**
     * Render the template's subject and body using sample data.
     *
     * @param array $overrides
     * @return array
     */
    public function renderWithSampleData(array $overrides = []): array
    {
        $data = array_merge($this->sample_data ?? [], $overrides);

        $subject = $this->replacePlaceholders($this->subject, $data);
        $body = $this->replacePlaceholders($this->body_html, $data);

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    /**
     * A helper function to replace placeholder variables in a string with actual data.
     *
     * @param string $content
     * @param array $data
     * @return string
     */
    public function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            // Changed to use single curly braces {variable} instead of double {{variable}}
            $placeholder = '{' . $key . '}';
            $content = str_replace($placeholder, $value, $content);
        }
        return $content;
    }

    /**
     * Scope a query to only include templates of a given category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include active templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

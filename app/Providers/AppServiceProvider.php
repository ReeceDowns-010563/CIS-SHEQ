<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Models\BrandingSetting;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make Carbon format dates in d/m/Y format for form inputs
        Carbon::macro('toHtmlDateString', function () {
            return $this->format('Y-m-d');
        });

        // Inject branding into all views
        View::composer('*', function ($view) {
            $view->with('branding', BrandingSetting::first());
        });

        // Register Blade directives for feature access checking
        Blade::directive('canAccessFeature', function ($featureKey) {
            return "<?php if(\\App\\Helpers\\FeatureHelper::canAccess($featureKey)): ?>";
        });

        Blade::directive('endCanAccessFeature', function () {
            return '<?php endif; ?>';
        });
    }
}

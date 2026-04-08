<?php 
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheableService
{

    /** @return mixed */
    protected function rememberWithTags(string $tag, string $keyIdentifier, callable $callback, int $ttl = 21600):mixed
    {
        $locale = app()->getLocale();
        
        $cacheKey = "{$tag}:{$keyIdentifier}:{$locale}";

        if (config('cache.default') === 'file' || config('cache.default') === 'database') {
            return Cache::remember($cacheKey, $ttl, $callback);
        }

        return Cache::tags([$tag])->remember($cacheKey, $ttl, $callback);
    }
}
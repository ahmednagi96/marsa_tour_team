<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheableService
{
    /**
     * تنفيذ الكاش مع التاغز والـ Pagination بشكل ديناميكي
     */
    protected function rememberWithTags(string $tag, string $keyIdentifier, callable $callback, int $ttl = 21600)
    {
        $page = request('page', 1);
        $perPage = request('per_page', 15);
        $locale = app()->getLocale();
        
        // تكوين Key فريد وشامل
        $cacheKey = "{$tag}_{$keyIdentifier}_{$locale}_p{$page}_l{$perPage}";

        return Cache::tags([$tag])->remember($cacheKey, $ttl, $callback);
    }
}
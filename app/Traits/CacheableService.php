<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheableService
{

    // /** @return mixed */
    // protected function rememberWithTags(string $tag, string $keyIdentifier, callable $callback, int $ttl = 21600):mixed
    // {
    //     $locale = app()->getLocale();
        
    //     $cacheKey = "{$tag}:{$keyIdentifier}:{$locale}";

    //     if (config('cache.default') === 'file' || config('cache.default') === 'database') {
    //         return Cache::remember($cacheKey, $ttl, $callback);
    //     }

    //     return Cache::tags([$tag])->remember($cacheKey, $ttl, $callback);
    // }

        public function rememberWithTags(string $tag, string $keyIdentifier, callable $callback, int $ttl = 21600): mixed
        {
            $locale = app()->getLocale();
            $cacheKey = "{$tag}:{$keyIdentifier}:{$locale}";

            // 1. تحديد الـ Store المناسب (عشان ندعم الـ Tags والـ File/Database في نفس الوقت)
            $store = (config('cache.default') === 'file' || config('cache.default') === 'database')
                ? Cache::getFacadeRoot()
                : Cache::tags([$tag]);

            // 2. نحاول نجيب الداتا الأول
            $value = $store->get($cacheKey);

            // 3. لو الداتا موجودة (ومش null)، رجعها فوراً ومتروحش للداتابيز
            if ($value !== null) {
                return $value;
            }

            // 4. لو مش موجودة، نفذ الـ callback (روح للداتابيز)
            $result = $callback();

            // 5. التاتش العبقري: خزن النتيجة فقط لو مش null
            // وبنستخدم الـ $store اللي هو أصلاً شايل الـ tags بتاعك
            if ($result !== null && $result !== false && $result !== []) {
                $store->put($cacheKey, $result, $ttl);
            }

            return $result;
        }
}

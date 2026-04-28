<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'users',
            'id'   => (string) ($this->uuid ?? $this->id), // استخدام UUID كمعيار أمان عالمي

            'attributes' => [
                // معلومات الحساب الأساسية
                'profile' => [
                    'name'         => $this->name ?? 'User',
                    'phone'        => $this->phone,
                    'email'        => $this->email,
                    'avatar_url'   => $this->formatAvatarUrl(),
                ],

                // معلومات الموقع
                'location' =>
                $this->whenLoaded("country", function () {
                    return [
                        'country'      => $this->country->name,
                        'country_code' => $this->country->country_code,
                    ];
                }),
                'verification_status' => [
                    'is_verified'        => !is_null($this->phone_verified_at) || !is_null($this->email_verified_at),
                    'phone_verified'     => !is_null($this->phone_verified_at),
                    'email_verified'     => !is_null($this->email_verified_at),
                    'verified_at'        => $this->phone_verified_at?->toIso8601String() ?? $this->email_verified_at?->toIso8601String(),
                ],
                // الحالة والصلاحيات (Capabilities)
                'status' => [
                    'is_active'    => (bool) ($this->is_active ?? true),
                    'last_login'   => $this->last_login_at?->diffForHumans(),
                ],

                // التوقيتات بصيغة الـ ISO الموحدة
                'timestamps' => [
                    'created_at' => $this->created_at?->toIso8601String(),
                    'updated_at' => $this->updated_at?->toIso8601String(),
                ],
            ],

            // الـ Relationships (اختياري: يظهر فقط إذا قمت بعمل Load للعلاقة)
            'relationships' => [
                'orders' => $this->whenLoaded('orders', function () {
                    return [
                        'count' => $this->orders_count ?? $this->orders->count(),
                        'links' => [
                            'related' => route('users.orders.index', $this->id)
                        ]
                    ];
                }),
            ],

            // الـ Meta Data الخاصة بهذا السجل فقط
            'meta' => [
                'permissions' => [
                    'can_edit'   => $request->user()?->can('update', $this->resource) ?? false,
                    'can_delete' => $request->user()?->can('delete', $this->resource) ?? false,
                ]
            ],

            'links' => [
                   'self' => route('auth.user.show'),
            ],
        ];
    }

    /**
     * وظيفة مساعدة للتعامل مع رابط الصورة باحترافية
     */
    private function formatAvatarUrl(): ?string
    {
        if (!$this->avatar) {
            // تقديم خدمة Default Avatar في حالة عدم وجود صورة
            return "https://ui-avatars.com/api/?name=" . urlencode($this->name ?? 'U') . "&color=7F9CF5&background=EBF4FF";
        }

        // التأكد من استخدام السيرفر الصحيح (Local or S3)
        return filter_var($this->avatar, FILTER_VALIDATE_URL)
            ? $this->avatar
            : Storage::disk('public')->url($this->avatar);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\SocialiteCallbackRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SocialiteController extends BaseController
{
    private const ALLOWED_PROVIDERS = ['google', 'github'];

    /**
     * Get the OAuth redirect URL for the provider.
     *
     * Frontend calls this, then redirects the user to the returned URL.
     */
    public function redirect(string $provider): JsonResponse
    {
        $this->validateProvider($provider);

        // Generate the OAuth redirect URL
        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return $this->success([
            'provider' => $provider,
            'redirect_url' => $url,

        ], "user redirect successfully to " . $provider, 200);
    }

    /**
     * Handle OAuth callback from the provider.
     *
     * Frontend receives the ?code=... from provider,
     * then sends it here to complete authentication.
     */
    public function callback(SocialiteCallbackRequest $request, string $provider): AuthResource
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();
        } catch (\Exception $e) {
            throw new BadRequestHttpException(
                'Failed to authenticate with provider: ' . $e->getMessage()
            );
        }

        if (empty($socialUser->getEmail())) {
            throw new BadRequestHttpException('Email not provided by the OAuth provider.');
        }

        // Find or create user
        $user = $this->findOrCreateUser($socialUser, $provider);

        // Delete old tokens (optional: enforce single active session)
        // $user->tokens()->delete();

        // Create new Sanctum token
        $token = $user->createToken(
            name: "{$provider}_auth",
            abilities: ['*'],
            expiresAt: now()->addMonths(2)
        )->plainTextToken;

        return new AuthResource([
            'user'  => $user,
            'token' => $token,
        ]);
    }


    /**
     * Find existing user or create a new one.
     * Links accounts if the same email exists from another provider.
     */
    private function findOrCreateUser(\Laravel\Socialite\Contracts\User $socialUser, string $provider): User
    {
        // 1. Try to find by provider + provider_id
        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($user) {
            // Update avatar in case it changed
            $user->update(['avatar' => $socialUser->getAvatar()]);
            return $user;
        }

        // 2. Try to find by email (account linking)
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update([
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar'      => $socialUser->getAvatar(),
            ]);
            return $user;
        }

        // 3. Create new user
        return User::create([
            'name'              => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
            'email'             => $socialUser->getEmail(),
            'provider'          => $provider,
            'provider_id'       => $socialUser->getId(),
            'avatar'            => $socialUser->getAvatar(),
            'password'          => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Validate provider is supported.
     */
    private function validateProvider(string $provider)
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            return $this->error("Unsupported provider. Allowed: google, github", 422);
        }
    }
}

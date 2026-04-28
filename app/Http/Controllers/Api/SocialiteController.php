<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\SocialiteCallbackRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SocialiteController extends BaseController
{
    private const ALLOWED_PROVIDERS = ['google', 'github'];

    /**
     * Get the OAuth redirect URL for the provider.
     */
    public function redirect(string $provider): JsonResponse
    {
        $this->validateProvider($provider);

        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return $this->success([
            'provider'     => $provider,
            'redirect_url' => $url,
        ], "Redirecting user to {$provider}", 200);
    }

    /**
     * Handle OAuth callback from the provider.
     */
    public function callback(SocialiteCallbackRequest $request, string $provider): AuthResource
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(
                'Failed to authenticate with provider: ' . $e->getMessage()
            );
        }

        if (empty($socialUser->getEmail())) {
            throw new BadRequestHttpException('Email not provided by the OAuth provider.');
        }

        $user = $this->findOrCreateUser($socialUser, $provider);

        return new AuthResource([
            'user'  => $user->load("country"),
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    /**
     * Find existing user or create a new one.
     */
    private function findOrCreateUser(SocialiteUser $socialUser, string $provider): User
    {
        $providerData = [
            'provider'          => $provider,
            'provider_id'       => $socialUser->getId(),
            'avatar'            => $socialUser->getAvatar(),
            'email_verified_at' => now(),
            'last_login_at'     => now(),
        ];

        // 1. Try to find by provider + provider_id
        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($user) {
            $user->update([
                'avatar'        => $socialUser->getAvatar(),
                'last_login_at' => now(),
            ]);
            return $user;
        }

        // 2. Try to find by email (account linking)
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update($providerData);
            return $user;
        }

        // 3. Create new user
        return User::create([
            'name'     => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
            'email'    => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            ...$providerData,
        ]);
    }

    /**
     * Validate that the provider is supported.
     *
     * @throws UnprocessableEntityHttpException
     */
    private function validateProvider(string $provider): void
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            throw new UnprocessableEntityHttpException(
                'Unsupported provider. Allowed: ' . implode(', ', self::ALLOWED_PROVIDERS)
            );
        }
    }
}
<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $role
 * @property string|null $profile_photo_path
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $tour_id
 * @property int $tour_availability_id
 * @property \Illuminate\Support\Carbon $travel_date
 * @property int $adults_count
 * @property int $children_count
 * @property string $adult_price
 * @property string $child_price
 * @property string $total_price
 * @property \App\Enums\BookingStatus $status
 * @property-read \App\Models\Tour $tour
 * @property-read \App\Models\TourAvailability $tourAvailability
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\BookingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereAdultPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereAdultsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereChildPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereChildrenCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTourAvailabilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTravelDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $photo
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ContactusTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactusTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
	class Contactus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $contactus_id
 * @property string $locale
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereContactusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ContactusTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $tour_id
 * @property string|null $offer_price_value
 * @property int|null $offer_price_percent
 * @property string|null $special_price_start
 * @property string|null $special_price_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tour $tour
 * @property-read \App\Models\OfferTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfferTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\OfferFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Offer listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPricePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPriceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereSpecialPriceEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereSpecialPriceStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
	class Offer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $offer_id
 * @property string $locale
 * @property string|null $offer_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\OfferTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereOfferName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class OfferTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property string $amount
 * @property string $currency
 * @property string $gateway
 * @property string|null $transaction_id
 * @property string $status
 * @property string|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PrivacyTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrivacyTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
	class Privacy extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $privacy_id
 * @property string $locale
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation wherePrivacyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PrivacyTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SocialTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Social listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Social orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Social orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Social orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Social query()
 * @method static \Illuminate\Database\Eloquent\Builder|Social translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Social translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
	class Social extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $social_id
 * @property string $locale
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class SocialTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $trip_id
 * @property int $duration
 * @property string $price
 * @property bool $is_active
 * @property bool $is_favourite
 * @property string|null $photos
 * @property string|null $video
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Offer|null $offer
 * @property-read \App\Models\TourTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TourTranslation> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Trip $trip
 * @method static \Database\Factories\TourFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tour listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Tour query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereIsFavourite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour withTranslation(?string $locale = null)
 * @mixin \Eloquent
 * @property string|null $discount_type
 * @property string|null $discount_value
 * @property string|null $sale_price
 * @property string|null $sale_start
 * @property string|null $sale_end
 * @property string $default_child_price
 * @property int $child_age_limit
 * @property bool $allows_children
 * @property-read float $current_price
 * @property-read bool $is_on_sale
 * @property-read mixed $saved_amount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TourAvailability> $tourAvailability
 * @property-read int|null $tour_availability_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tour active(?bool $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour discountTours(?bool $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour favourite(?bool $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour filter(?string $searchTerm)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereAllowsChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereChildAgeLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDefaultChildPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereSaleEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereSaleStart($value)
 */
	class Tour extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $tour_id
 * @property string $date
 * @property int $capacity
 * @property int $booked
 * @property string $adult_price
 * @property string|null $child_price
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $available_seats
 * @property-read mixed $final_child_price
 * @property-read \App\Models\Tour $tour
 * @method static \Database\Factories\TourAvailabilityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability query()
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereAdultPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereBooked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereChildPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourAvailability whereUpdatedAt($value)
 */
	class TourAvailability extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $tour_id
 * @property string $locale
 * @property string $name
 * @property string|null $country
 * @property string|null $city
 * @property string|null $street
 * @property string|null $description
 * @property string|null $services
 * @property string|null $additional_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TourTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereAdditionalData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class TourTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tour> $tours
 * @property-read int|null $tours_count
 * @property-read \App\Models\TripTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TripTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\TripFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Trip listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Trip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip withTranslation(?string $locale = null)
 * @mixin \Eloquent
 * @property \App\Enums\TripTrending $trending
 * @property-read mixed $photo_url
 * @method static \Illuminate\Database\Eloquent\Builder|Trip filter(?string $searchTerm)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip trend(?bool $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTrending($value)
 */
	class Trip extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $trip_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tour> $tours
 * @property-read int|null $tours_count
 * @method static \Database\Factories\TripTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class TripTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $country
 * @property string|null $country_code
 * @property string $phone
 * @property string|null $photo
 * @property string|null $code
 * @property string|null $expired_at
 * @property string|null $fcm_token
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}


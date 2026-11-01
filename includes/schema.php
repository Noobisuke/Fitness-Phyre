<?php
/**
 * LocalBusiness structured data (JSON-LD) for Google.
 * Only facts that exist in config/site.php are included. Empty values are left out.
 * aggregateRating is deliberately NOT included: we do not have the review count,
 * and Google does not allow businesses to mark up their own ratings anyway.
 */
function local_business_schema(): string
{
    $address = [
        '@type'           => 'PostalAddress',
        'streetAddress'   => site('address_line'),
        'addressLocality' => site('city'),
        'addressRegion'   => site('state'),
        'addressCountry'  => 'IN',
    ];
    if (site('postal_code')) {
        $address['postalCode'] = site('postal_code');
    }

    $data = [
        '@context'  => 'https://schema.org',
        '@type'     => 'ExerciseGym',
        'name'      => site('name'),
        'telephone' => site('phone_e164'),
        'address'   => $address,
        'geo'       => ['@type' => 'GeoCoordinates', 'latitude' => site('lat'), 'longitude' => site('lng')],
        'hasMap'    => site('maps_url'),
    ];

    if (site('site_url')) {
        $data['url'] = rtrim(site('site_url'), '/') . '/';
    }
    if (site('email')) {
        $data['email'] = site('email');
    }
    if (site('logo') && site('site_url')) {
        $data['image'] = rtrim(site('site_url'), '/') . '/' . ltrim(site('logo'), '/');
    }
    $social = array_values(array_filter(site('social')));
    if ($social) {
        $data['sameAs'] = $social;
    }

    // Opening hours only when verified
    if (function_exists('hours_available') && hours_available()) {
        $spec = [];
        foreach (site('hours') as $day => $h) {
            if (!empty($h['open']) && !empty($h['close'])) {
                $spec[] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $day, 'opens' => $h['open'], 'closes' => $h['close']];
            }
        }
        $data['openingHoursSpecification'] = $spec;
    }

    // JSON_HEX_* keeps the output safe inside a <script> tag.
    return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP);
}

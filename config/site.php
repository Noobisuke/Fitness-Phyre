<?php
/**
 * CENTRAL BUSINESS CONFIGURATION
 * ------------------------------------------------------------
 * Every business detail used on the website lives here.
 * Change a value once and it updates on every page.
 *
 * Values marked  NEEDS CONFIRMATION  have NOT been verified with
 * Fitness Phyre. Leave them empty (or false) until confirmed.
 * Nothing empty is ever shown to visitors as if it were fact.
 */

return [

    // ---- Basic identity (provided) ------------------------------------
    'name'     => 'Fitness Phyre',
    'tagline'  => 'Gym & Fitness Centre in Ludhiana',

    // ---- Contact (phone provided) -------------------------------------
    'phone_display'  => '+91 98031 58000',
    'phone_e164'     => '+919803158000',   // used in tel: links
    'whatsapp_number'=> '919803158000',    // digits only, country code first (no + sign)
    'email'          => '',                // NEEDS CONFIRMATION - leave empty to hide

    // ---- Address & map (provided) -------------------------------------
    'address_line'   => 'Karnail Singh Nagar Phase-II, near Pakhowal Road',
    'city'           => 'Ludhiana',
    'state'          => 'Punjab',
    'country'        => 'India',
    'postal_code'    => '',                // NEEDS CONFIRMATION - leave empty to omit from schema
    'lat'            => 30.876209,
    'lng'            => 75.820504,
    'maps_url'       => 'https://www.google.com/maps/place/Fitness+Phyre/@30.876209,75.820504',

    // ---- Public rating (publicly listed on Google) --------------------
    // Re-check this number on Google Maps before launch; it changes over time.
    'rating'         => '4.6',

    // ---- Opening hours -------------------------------------------------
    // NEEDS CONFIRMATION. Until verified, keep 'verified' => false and
    // the site will say "Please call to confirm timings" instead of times.
    // Once confirmed, fill the times (24h "HH:MM") and set verified => true.
    // Use null for a closed day.
    'hours_verified' => false,
    'hours' => [
        'Monday'    => ['open' => '', 'close' => ''],
        'Tuesday'   => ['open' => '', 'close' => ''],
        'Wednesday' => ['open' => '', 'close' => ''],
        'Thursday'  => ['open' => '', 'close' => ''],
        'Friday'    => ['open' => '', 'close' => ''],
        'Saturday'  => ['open' => '', 'close' => ''],
        'Sunday'    => ['open' => '', 'close' => ''],
    ],

    // ---- Social links (none confirmed) --------------------------------
    // NEEDS CONFIRMATION. Add full URLs only if the accounts really exist.
    'social' => [
        'instagram' => '',
        'facebook'  => '',
        'youtube'   => '',
    ],

    // ---- Website settings ---------------------------------------------
    // Leave '' when the site is at the domain root (https://example.com/).
    // If it sits in a sub-folder (http://localhost/fitness-phyre) set '/fitness-phyre'.
    'base_url'       => '',
    'site_url'       => '',   // e.g. 'https://www.fitnessphyre.in' - set at deployment
    'logo'           => '',   // NEEDS CONFIRMATION - e.g. 'assets/images/logo.png'; empty = text logo

    // ---- Programs (edit freely) ----------------------------------------
    // 'confirmed' => false shows a small "Ask us" note. Set true once the
    // gym confirms the program is currently offered.
    'programs' => [
        ['slug' => 'strength',   'name' => 'Strength Training',   'confirmed' => false,
         'text' => 'Resistance training to build strength at your own pace.',
         'suited' => 'Beginners and regular lifters'],
        ['slug' => 'bodybuilding','name' => 'Bodybuilding',        'confirmed' => false,
         'text' => 'Muscle-focused training with structured splits and progressive loading.',
         'suited' => 'Members with muscle-building goals'],
        ['slug' => 'cardio',     'name' => 'Cardio',              'confirmed' => false,
         'text' => 'Build stamina and general fitness with regular cardio work.',
         'suited' => 'Everyone, any fitness level'],
        ['slug' => 'functional', 'name' => 'Functional Training', 'confirmed' => false,
         'text' => 'Movement-based exercises that help with everyday strength and mobility.',
         'suited' => 'Those who want all-round fitness'],
        ['slug' => 'crossfit',   'name' => 'CrossFit-style Training','confirmed' => false,
         'text' => 'Varied, higher-intensity circuits mixing strength and conditioning.',
         'suited' => 'Members who like intense, varied sessions'],
        ['slug' => 'aerobics',   'name' => 'Aerobics',            'confirmed' => false,
         'text' => 'Led, music-based group sessions for cardio and coordination.',
         'suited' => 'Those who prefer group workouts'],
        ['slug' => 'zumba',      'name' => 'Zumba',               'confirmed' => false,
         'text' => 'Dance-based group fitness that keeps workouts fun.',
         'suited' => 'Anyone who enjoys dance and music'],
        ['slug' => 'general',    'name' => 'General Fitness',     'confirmed' => false,
         'text' => 'A balanced mix of strength and cardio to stay active and consistent.',
         'suited' => 'First-time gym-goers'],
        ['slug' => 'weight',     'name' => 'Weight Management',   'confirmed' => false,
         'text' => 'A routine and guidance to support healthy, steady weight goals.',
         'suited' => 'Members working on weight goals'],
    ],

    // ---- Anti-spam / admin (used in later phases) ---------------------
    'rate_limit_per_hour' => 5,   // max form submissions per IP per hour
];

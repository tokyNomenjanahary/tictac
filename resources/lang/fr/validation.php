<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted" => i18n("validation.accepted"),
    "active_url" => i18n("validation.active_url"),
    "after" => i18n("validation.after"),
    "after_or_equal" => i18n("validation.after_or_equal"),
    "alpha" => i18n("validation.alpha"),
    "alpha_dash" => i18n("validation.alpha_dash"),
    "alpha_num" => i18n("validation.alpha_num"),
    "array" => i18n("validation.array"),
    "before" => i18n("validation.before"),
    'between'              => [
        'numeric' => i18n('validation.between.numeric'),
        'file'    => i18n('validation.between.file'),
        'string'  => i18n('validation.between.string'),
        'array'   => i18n('validation.between.array'),
    ],
    "boolean" => i18n("validation.boolean"),
    "confirmed" => i18n("validation.confirmed"),
    "date" => i18n("validation.date"),
    "date_format" => i18n("validation.date_format"),
    "different" => i18n("validation.different"),
    "digits" => i18n("validation.digits"),
    "digits_between" => i18n("validation.digits_between"),
    "dimensions" => i18n("validation.dimensions"),
    "distinct" => i18n("validation.distinct"),
    "email" => i18n("validation.email"),
    "exists" => i18n("validation.exists"),
    "file" => i18n("validation.file"),
    "filled" => i18n("validation.filled"),
    "image" => i18n("validation.image"),
    "in" => i18n("validation.in"),
    "in_array" => i18n("validation.in_array"),
    "integer" => i18n("validation.integer"),
    "ip" => i18n("validation.ip"),
    "ipv4" => i18n("validation.ipv4"),
    "ipv6" => i18n("validation.ipv6"),
    "json" => i18n("validation.json"),
    'max'                  => [
        'numeric' => i18n('validation.max.numeric'),
        'file'    => i18n('validation.max.file'),
        'string'  => i18n('validation.max.string'),
        'array'   => i18n('validation.max.array'),
    ],
    "mimes" => i18n("validation.mimes"),
    "mimetypes" => i18n("validation.mimetypes"),
    'min'                  => [
        'numeric' => i18n('validation.min.numeric'),
        'file'    => i18n('validation.min.file'),
        'string'  => i18n('validation.min.string'),
        'array'   => i18n('validation.min.array'),
    ],
    "not_in" => i18n("validation.not_in"),
    "numeric" => i18n("validation.numeric"),
    "present" => i18n("validation.present"),
    "regex" => i18n("validation.regex"),
    "required" => i18n("validation.required"),
    "required_if" => i18n("validation.required_if"),
    "required_unless" => i18n("validation.required_unless"),
    "required_with" => i18n("validation.required_with"),
    "required_with_all" => i18n("validation.required_with_all"),
    "required_without" => i18n("validation.required_without"),
    "required_without_all" => i18n("validation.required_without_all"),
    "same" => i18n("validation.same"),
    'size'                 => [
        'numeric' => i18n('validation.size.numeric'),
        'file'    => i18n('validation.size.file'),
        'string'  => i18n('validation.size.string'),
        'array'   => i18n('validation.size.array'),
    ],
    "string" => i18n("validation.string"),
    "timezone" => i18n("validation.timezone"),
    "unique" => i18n("validation.unique"),
    "uploaded" => i18n("validation.uploaded"),
    "url" => i18n("validation.url"),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => i18n('validation.custom.attribute-name'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];

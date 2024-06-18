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

    "accepted" => i18n("validation.accepted",'en'),
    "active_url" => i18n("validation.active_url",'en'),
    "after" => i18n("validation.after",'en'),
    "after_or_equal" => i18n("validation.after_or_equal",'en'),
    "alpha" => i18n("validation.alpha",'en'),
    "alpha_dash" => i18n("validation.alpha_dash",'en'),
    "alpha_num" => i18n("validation.alpha_num",'en'),
    "array" => i18n("validation.array",'en'),
    "before" => i18n("validation.before",'en'),
    'between'              => [
        'numeric' => i18n('validation.between.numeric','en'),
        'file'    => i18n('validation.between.file','en'),
        'string'  => i18n('validation.between.string','en'),
        'array'   => i18n('validation.between.array','en'),
    ],
    "boolean" => i18n("validation.boolean",'en'),
    "confirmed" => i18n("validation.confirmed",'en'),
    "date" => i18n("validation.date",'en'),
    "date_format" => i18n("validation.date_format",'en'),
    "different" => i18n("validation.different",'en'),
    "digits" => i18n("validation.digits",'en'),
    "digits_between" => i18n("validation.digits_between",'en'),
    "dimensions" => i18n("validation.dimensions",'en'),
    "distinct" => i18n("validation.distinct",'en'),
    "email" => i18n("validation.email",'en'),
    "exists" => i18n("validation.exists",'en'),
    "file" => i18n("validation.file",'en'),
    "filled" => i18n("validation.filled",'en'),
    "image" => i18n("validation.image",'en'),
    "in" => i18n("validation.in",'en'),
    "in_array" => i18n("validation.in_array",'en'),
    "integer" => i18n("validation.integer",'en'),
    "ip" => i18n("validation.ip",'en'),
    "ipv4" => i18n("validation.ipv4",'en'),
    "ipv6" => i18n("validation.ipv6",'en'),
    "json" => i18n("validation.json",'en'),
    'max'                  => [
        'numeric' => i18n('validation.max.numeric','en'),
        'file'    => i18n('validation.max.file','en'),
        'string'  => i18n('validation.max.string','en'),
        'array'   => i18n('validation.max.array','en'),
    ],
    "mimes" => i18n("validation.mimes",'en'),
    "mimetypes" => i18n("validation.mimetypes",'en'),
    'min'                  => [
        'numeric' => i18n('validation.min.numeric','en'),
        'file'    => i18n('validation.min.file','en'),
        'string'  => i18n('validation.min.string','en'),
        'array'   => i18n('validation.min.array','en'),
    ],
    "not_in" => i18n("validation.not_in",'en'),
    "numeric" => i18n("validation.numeric",'en'),
    "present" => i18n("validation.present",'en'),
    "regex" => i18n("validation.regex",'en'),
    "required" => i18n("validation.required",'en'),
    "required_if" => i18n("validation.required_if",'en'),
    "required_unless" => i18n("validation.required_unless",'en'),
    "required_with" => i18n("validation.required_with",'en'),
    "required_with_all" => i18n("validation.required_with_all",'en'),
    "required_without" => i18n("validation.required_without",'en'),
    "required_without_all" => i18n("validation.required_without_all",'en'),
    "same" => i18n("validation.same",'en'),
    'size'                 => [
        'numeric' => i18n('validation.size.numeric','en'),
        'file'    => i18n('validation.size.file','en'),
        'string'  => i18n('validation.size.string','en'),
        'array'   => i18n('validation.size.array','en'),
    ],
    "string" => i18n("validation.string",'en'),
    "timezone" => i18n("validation.timezone",'en'),
    "unique" => i18n("validation.unique",'en'),
    "uploaded" => i18n("validation.uploaded",'en'),
    "url" => i18n("validation.url",'en'),

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
            'rule-name' => i18n('validation.custom.attribute-name','en'),
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

<?php

return [

    /*
    |
    | Validation Language Lines
    |
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    
    'accepted' => 'សូមយល់ព្រមលើ :attribute field។',
    'accepted_if' => 'សូមយល់ព្រមលើ :attribute field នៅពេល :other មានតម្លៃ :value។',
    'active_url' => ':attribute field ត្រូវតែជា URL ត្រឹមត្រូវ។',
    'after' => ':attribute field ត្រូវតែជាកាលបរិច្ឆេទក្រោយពី :date។',
    'after_or_equal' => ':attribute field ត្រូវតែជាកាលបរិច្ឆេទក្រោយ ឬស្មើនឹង :date។',
    'alpha' => ':attribute field ត្រូវតែមានតែអក្សរប៉ុណ្ណោះ។',
    'alpha_dash' => ':attribute field ត្រូវតែមានតែអក្សរ លេខ សញ្ញាដក និងសញ្ញាគូសក្រោមប៉ុណ្ណោះ។',
    'alpha_num' => ':attribute field ត្រូវតែមានតែអក្សរ និងលេខប៉ុណ្ណោះ។',
    'any_of' => ':attribute field មិនត្រឹមត្រូវទេ។',
    'array' => ':attribute field ត្រូវតែជាអារេ។',
    'ascii' => ':attribute field ត្រូវតែមានតែតួអក្សរ និងនិមិត្តសញ្ញា ASCII ប៉ុណ្ណោះ។',
    'before' => ':attribute field ត្រូវតែជាកាលបរិច្ឆេទមុន :date។',
    'before_or_equal' => ':attribute field ត្រូវតែជាកាលបរិច្ឆេទមុន ឬស្មើនឹង :date។',
    'between' => [
        'array' => ':attribute field ត្រូវតែមានចំនួន items ចន្លោះពី :min ដល់ :max។',
        'file' => ':attribute field ត្រូវមានទំហំចន្លោះពី :min ដល់ :max kilobytes។',
        'numeric' => ':attribute field ត្រូវមានតម្លៃចន្លោះពី :min ដល់ :max។',
        'string' => ':attribute field ត្រូវមានតួអក្សរចន្លោះពី :min ដល់ :max។',
    ],
    'boolean' => ':attribute field ត្រូវតែជាតម្លៃពិត ឬមិនពិត។',
    'can' => ':attribute field មានតម្លៃដែលមិនត្រូវបានអនុញ្ញាត។',
    'confirmed' => 'ការបញ្ជាក់ :attribute field មិនត្រូវគ្នាទេ។',
    'contains' => ':attribute field ខ្វះតម្លៃដែលត្រូវការ។',
    'current_password' => 'ពាក្យសម្ងាត់មិនត្រឹមត្រូវទេ។',
    'date' => ':attribute field ត្រូវតែជាកាលបរិច្ឆេទត្រឹមត្រូវ។',
    'date_equals' => ':attribute field ត្រូវតែជាកាលបរិច្ឆេទស្មើនឹង :date។',
    'date_format' => ':attribute field ត្រូវតែតាម format :format។',
    'decimal' => ':attribute field ត្រូវតែមានខ្ទង់ទសភាគចំនួន :decimal។',
    'declined' => ':attribute field ត្រូវតែត្រូវបានបដិសេធ។',
    'declined_if' => ':attribute field ត្រូវតែត្រូវបានបដិសេធ នៅពេល :other មានតម្លៃ :value។',
    'different' => ':attribute field និង :other ត្រូវតែមានតម្លៃខុសគ្នា។',
    'digits' => ':attribute field ត្រូវតែមានចំនួន :digits ខ្ទង់។',
    'digits_between' => ':attribute field ត្រូវតែមានចំនួនខ្ទង់ចន្លោះពី :min ដល់ :max។',
    'dimensions' => ':attribute field មានទំហំរូបភាពមិនត្រឹមត្រូវ។',
    'distinct' => ':attribute field មានតម្លៃស្ទួន។',
    'doesnt_contain' => ':attribute field មិនត្រូវមានតម្លៃណាមួយក្នុងចំណោមតម្លៃខាងក្រោមទេ៖ :values។',
    'doesnt_end_with' => ':attribute field មិនត្រូវបញ្ចប់ដោយតម្លៃណាមួយក្នុងចំណោមតម្លៃខាងក្រោមទេ៖ :values។',
    'doesnt_start_with' => ':attribute field មិនត្រូវចាប់ផ្តើមដោយតម្លៃណាមួយក្នុងចំណោមតម្លៃខាងក្រោមទេ៖ :values។',
    'email' => ':attribute field ត្រូវតែជាអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវ។',
    'encoding' => ':attribute field ត្រូវតែ encoded ដោយប្រើ encoding :encoding។',
    'ends_with' => ':attribute field ត្រូវតែបញ្ចប់ដោយតម្លៃណាមួយក្នុងចំណោមតម្លៃខាងក្រោម៖ :values។',
    'enum' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
    'exists' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
    'extensions' => ':attribute field ត្រូវតែជាឯកសារដែលមានផ្នែកបន្ថែមមួយក្នុងចំណោម៖ :values។',
    'file' => ':attribute field ត្រូវតែជាឯកសារ។',
    'filled' => ':attribute field ត្រូវតែមានតម្លៃ។',
    'gt' => [
        'array' => ':attribute field ត្រូវតែមានចំនួន items លើសពី :value។',
        'file' => ':attribute field ត្រូវមានទំហំលើសពី :value kilobytes។',
        'numeric' => ':attribute field ត្រូវតែធំជាង :value។',
        'string' => ':attribute field ត្រូវមានតួអក្សរលើសពី :value។',
    ],
    'gte' => [
        'array' => ':attribute field ត្រូវតែមានចំនួន items យ៉ាងតិច :value។',
        'file' => ':attribute field ត្រូវមានទំហំធំជាង ឬស្មើនឹង :value kilobytes។',
        'numeric' => ':attribute field ត្រូវតែធំជាង ឬស្មើនឹង :value។',
        'string' => ':attribute field ត្រូវមានតួអក្សរយ៉ាងតិច :value។',
    ],
    'hex_color' => ':attribute field ត្រូវតែជាកូដពណ៌ hexadecimal ត្រឹមត្រូវ។',
    'image' => ':attribute field ត្រូវតែជារូបភាព។',
    'in' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
    'in_array' => ':attribute field ត្រូវតែមាននៅក្នុង :other។',
    'in_array_keys' => ':attribute field ត្រូវតែមានយ៉ាងហោចណាស់ key មួយក្នុងចំណោម keys ខាងក្រោម៖ :values។',
    'integer' => ':attribute field ត្រូវតែជាចំនួនគត់។',
    'ip' => ':attribute field ត្រូវតែជាអាសយដ្ឋាន IP ត្រឹមត្រូវ។',
    'ipv4' => ':attribute field ត្រូវតែជាអាសយដ្ឋាន IPv4 ត្រឹមត្រូវ។',
    'ipv6' => ':attribute field ត្រូវតែជាអាសយដ្ឋាន IPv6 ត្រឹមត្រូវ។',
    'json' => ':attribute field ត្រូវតែជា JSON string ត្រឹមត្រូវ។',
    'list' => ':attribute field ត្រូវតែជាបញ្ជី។',
    'lowercase' => ':attribute field ត្រូវតែប្រើអក្សរតូច។',
    'lt' => [
        'array' => ':attribute field ត្រូវតែមានចំនួន items តិចជាង :value។',
        'file' => ':attribute field ត្រូវមានទំហំតិចជាង :value kilobytes។',
        'numeric' => ':attribute field ត្រូវតែតូចជាង :value។',
        'string' => ':attribute field ត្រូវមានតួអក្សរតិចជាង :value។',
    ],
    'lte' => [
        'array' => ':attribute field មិនត្រូវមានចំនួន items លើសពី :value ទេ។',
        'file' => ':attribute field ត្រូវមានទំហំតូចជាង ឬស្មើនឹង :value kilobytes។',
        'numeric' => ':attribute field ត្រូវតែតូចជាង ឬស្មើនឹង :value។',
        'string' => ':attribute field មិនត្រូវមានតួអក្សរលើសពី :value ទេ។',
    ],
    'mac_address' => ':attribute field ត្រូវតែជាអាសយដ្ឋាន MAC ត្រឹមត្រូវ។',
    'max' => [
        'array' => ':attribute field មិនត្រូវមានចំនួន items លើសពី :max ទេ។',
        'file' => ':attribute field មិនត្រូវមានទំហំលើសពី :max kilobytes ទេ។',
        'numeric' => ':attribute field មិនត្រូវលើសពី :max ទេ។',
        'string' => ':attribute field មិនត្រូវមានតួអក្សរលើសពី :max ទេ។',
    ],
    'max_digits' => ':attribute field មិនត្រូវមានខ្ទង់លើសពី :max ទេ។',
    'mimes' => ':attribute field ត្រូវតែជាឯកសារប្រភេទ៖ :values។',
    'mimetypes' => ':attribute field ត្រូវតែជាឯកសារប្រភេទ៖ :values។',
    'min' => [
        'array' => ':attribute field ត្រូវតែមានចំនួន items យ៉ាងតិច :min។',
        'file' => ':attribute field ត្រូវមានទំហំយ៉ាងតិច :min kilobytes។',
        'numeric' => ':attribute field ត្រូវតែមានតម្លៃយ៉ាងតិច :min។',
        'string' => ':attribute field ត្រូវមានតួអក្សរយ៉ាងតិច :min។',
    ],
    'min_digits' => ':attribute field ត្រូវតែមានខ្ទង់យ៉ាងតិច :min។',
    'missing' => ':attribute field ត្រូវតែអវត្តមាន។',
    'missing_if' => ':attribute field ត្រូវតែអវត្តមាន នៅពេល :other មានតម្លៃ :value។',
    'missing_unless' => ':attribute field ត្រូវតែអវត្តមាន លុះត្រាតែ :other មានតម្លៃ :value។',
    'missing_with' => ':attribute field ត្រូវតែអវត្តមាន នៅពេលមាន :values។',
    'missing_with_all' => ':attribute field ត្រូវតែអវត្តមាន នៅពេលមាន :values ទាំងអស់។',
    'multiple_of' => ':attribute field ត្រូវតែជាពហុគុណនៃ :value។',
    'not_in' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
    'not_regex' => ':attribute field format មិនត្រឹមត្រូវទេ។',
    'numeric' => ':attribute field ត្រូវតែជាលេខ។',
    'password' => [
        'letters' => ':attribute field ត្រូវតែមានអក្សរយ៉ាងតិចមួយ។',
        'mixed' => ':attribute field ត្រូវតែមានអក្សរធំ និងអក្សរតូចយ៉ាងតិចមួយៗ។',
        'numbers' => ':attribute field ត្រូវតែមានលេខយ៉ាងតិចមួយ។',
        'symbols' => ':attribute field ត្រូវតែមាននិមិត្តសញ្ញាយ៉ាងតិចមួយ។',
        'uncompromised' => ':attribute ដែលបានផ្តល់ត្រូវបានរកឃើញក្នុងការលេចធ្លាយទិន្នន័យ។ សូមជ្រើសរើស :attribute ផ្សេងទៀត។',
    ],
    'present' => ':attribute field ត្រូវតែមាននៅក្នុងសំណើ។',
    'present_if' => ':attribute field ត្រូវតែមាននៅក្នុងសំណើ នៅពេល :other មានតម្លៃ :value។',
    'present_unless' => ':attribute field ត្រូវតែមាននៅក្នុងសំណើ លុះត្រាតែ :other មានតម្លៃ :value។',
    'present_with' => ':attribute field ត្រូវតែមាននៅក្នុងសំណើ នៅពេលមាន :values។',
    'present_with_all' => ':attribute field ត្រូវតែមាននៅក្នុងសំណើ នៅពេលមាន :values ទាំងអស់។',
    'prohibited' => 'មិនអនុញ្ញាតឱ្យមាន :attribute field ទេ។',
    'prohibited_if' => 'មិនអនុញ្ញាតឱ្យមាន :attribute field ទេ នៅពេល :other មានតម្លៃ :value។',
    'prohibited_if_accepted' => 'មិនអនុញ្ញាតឱ្យមាន :attribute field ទេ នៅពេល :other ត្រូវបានយល់ព្រម។',
    'prohibited_if_declined' => 'មិនអនុញ្ញាតឱ្យមាន :attribute field ទេ នៅពេល :other ត្រូវបានបដិសេធ។',
    'prohibited_unless' => 'មិនអនុញ្ញាតឱ្យមាន :attribute field ទេ លុះត្រាតែ :other មានតម្លៃក្នុងចំណោម :values។',
    'prohibits' => ':attribute field មិនអនុញ្ញាតឱ្យមាន :other ទេ។',
    'regex' => ':attribute field format មិនត្រឹមត្រូវទេ។',
    'required' => ':attribute field ត្រូវតែបំពេញ។',
    'required_array_keys' => ':attribute field ត្រូវតែមាន entries សម្រាប់៖ :values។',
    'required_if' => ':attribute field ត្រូវតែបំពេញ នៅពេល :other មានតម្លៃ :value។',
    'required_if_accepted' => ':attribute field ត្រូវតែបំពេញ នៅពេល :other ត្រូវបានយល់ព្រម។',
    'required_if_declined' => ':attribute field ត្រូវតែបំពេញ នៅពេល :other ត្រូវបានបដិសេធ។',
    'required_unless' => ':attribute field ត្រូវតែបំពេញ លុះត្រាតែ :other មានតម្លៃក្នុងចំណោម :values។',
    'required_with' => ':attribute field ត្រូវតែបំពេញ នៅពេលមាន :values។',
    'required_with_all' => ':attribute field ត្រូវតែបំពេញ នៅពេលមាន :values ទាំងអស់។',
    'required_without' => ':attribute field ត្រូវតែបំពេញ នៅពេលគ្មាន :values។',
    'required_without_all' => ':attribute field ត្រូវតែបំពេញ នៅពេលគ្មាន :values ណាមួយទាំងអស់។',
    'same' => ':attribute field ត្រូវតែដូចគ្នានឹង :other។',
    'size' => [
        'array' => ':attribute field ត្រូវតែមានចំនួន :size items។',
        'file' => ':attribute field ត្រូវមានទំហំ :size kilobytes។',
        'numeric' => ':attribute field ត្រូវតែស្មើនឹង :size។',
        'string' => ':attribute field ត្រូវតែមានតួអក្សរចំនួន :size។',
    ],
    'starts_with' => ':attribute field ត្រូវតែចាប់ផ្តើមដោយតម្លៃណាមួយក្នុងចំណោម៖ :values។',
    'string' => ':attribute field ត្រូវតែជាខ្សែអក្សរ។',
    'timezone' => ':attribute field ត្រូវតែជាតំបន់ពេលវេលាត្រឹមត្រូវ។',
    'unique' => ':attribute ត្រូវបានប្រើរួចហើយ។',
    'uploaded' => 'ការបង្ហោះ :attribute បានបរាជ័យ។',
    'uppercase' => ':attribute field ត្រូវតែប្រើអក្សរធំ។',
    'url' => ':attribute field ត្រូវតែជា URL ត្រឹមត្រូវ។',
    'ulid' => ':attribute field ត្រូវតែជា ULID ត្រឹមត្រូវ។',
    'uuid' => ':attribute field ត្រូវតែជា UUID ត្រឹមត្រូវ។',
    /*
    |
    | Custom Validation Language Lines
    |
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |
    | Custom Validation Attributes
    |
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

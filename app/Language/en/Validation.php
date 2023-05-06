<?php

// override core en language system validation or define your own en language validation message
return [
    'property_value_update' => 'Category already contains a tag with this VALUE!',
    'user_name_update'      => 'User with this NAME already exists!',
    'user_email_update'     => 'User with this EMAIL already exists!',
    'user_email'            => 'User with this email adress does not exist!',
    'user_password'         => 'Invalid password!',
    'valid_status'          => 'Invalid status, should be one of: <pre>' . print_r(\App\Entities\Cast\StatusCast::VALID_VALUES, true) . '</pre>',
    'valid_tag'             => 'Circular dependency deteceted at TAG!',
];

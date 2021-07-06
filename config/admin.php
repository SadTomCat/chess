<?php

return [
    /*
     * Key is role of user that try update,
     * Value is roles that can be set and accesses roles of the user for update.
     * */
    'mapped_available_roles_for_update' => [
        'moderator' => ['support', 'user'],
    ],
];

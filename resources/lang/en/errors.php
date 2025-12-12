<?php

return [
    'message' => 'An unexpected error has occurred.',
    // Generic titles
    'title' => 'Something went wrong',
    'back_home' => 'Back to Home',

    // Specific errors
    403 => [
        'title' => 'Forbidden',
        'message' => 'You do not have permission to access this resource.',
    ],
    404 => [
        'title' => 'Page Not Found',
        'message' => 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.',
    ],
    419 => [
        'title' => 'Page Expired',
        'message' => 'The page has expired due to inactivity. Please refresh and try again.',
    ],
    429 => [
        'title' => 'Too Many Requests',
        'message' => 'You have sent too many requests in a short period. Please slow down and try again later.',
    ],
    500 => [
        'title' => 'Server Error',
        'message' => 'The server encountered an internal error. Our team has been notified.',
    ],
    503 => [
        'title' => 'Service Unavailable',
        'message' => 'The service is temporarily unavailable (maintenance). Please try again later.',
    ]
];

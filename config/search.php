<?php

return [
    /* Admins can override this comma-separated list with SEARCH_TRENDING. */
    'trending' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('SEARCH_TRENDING', 'GST Calculator,EMI Calculator,PDF Compressor,Image Resizer,Password Generator,JSON Formatter'))
    ))),
];

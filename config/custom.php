<?php

return [

    'RAZORPAY_KEY' =>  env('RAZORPAY_KEY'),
    'RAZORPAY_SECRET' => env('RAZORPAY_SECRET'),


    // 'NO_IMG' => url('/public/assets/no_image.jpg'),


    'ADMIN_ROUTE_NAME' => 'admin',
    'FACULTY_ROUTE_NAME' => 'faculty',

    'SADMIN_ROUTE_NAME' => 'sadmin',

    'admin_email' => env('ADMIN_EMAIL'),
    'from_email' => 'info@blindwelfaresociety.in',
    'order_prefix' => 'SJ',

    'order_status_arr' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'failed' => 'Failed',
        'success' => 'Success',

    ],
    'payment_status_arr' => [
        'not_paid' => 'Not Paid',
        'paid'     => 'Paid',
        'refunded' => 'Refunded',
        'free'     => 'Free',
    ],
    'payment_method' => [
        'card' => 'Card',
        'paypal' => 'PayPal',
        'cash' => 'Cash',
    ],

    'img_extension' => ['jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'],

    'compare_scope' => [
        '=' => '=',
        '>' => '>',
        '<' => '<',
        '>=' => '>=',
        '<=' => '<='
    ],

    'currency_arr' => [
        'USD' => 'USD',
        'EUR' => 'EUR',
        'INR' => 'INR',
        'AUD' => 'AUD',
        'GBP' => 'GBP'
    ],

    'currency_symbol_arr' => [
        'USD' => "&#36;",
        'EUR' => "&#128;",
        'INR' => "&#x20B9;",
        'AUD' => "A&#36;",
        'GBP' => "&#163;"
    ],

    'product_stamps_arr' => [
        'selling_fast' => "Selling fast",
        'sold_out' => "Sold out",
        'free_shipping' => "Free-shipping"
    ],

    'device_types_arr' => [
        'desktop' => "Desktop",
        'mobile' => "Mobile"
    ],

    'products_sort_by_arr' => [
        'price_high_low' => 'Price: High to Low',
        'price_low_high' => 'Price: Low to High',
        'new' => 'What\'s new',
        'popularity' => 'Popularity',
        'discount' => 'Discount',
    ],

    'gst_arr' => [
        '5' => '5%',
        '12' => '12%',
    ],

    'input_types_arr' => [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio',
        'file' => 'File',
        'email' => 'Email',
    ],

    'setting_types_arr' => [
        'website' => 'Website',
        'seo' => 'SEO',
        'social_links' => 'Social Links',
    ],

    'blog_type_arr' => [
        'blogs' => 'Blogs',
        'news' => 'News',
    ],

    'menu_link_type_arr' => [
        'internal' => 'Internal',
        'external' => 'External',
        'category' => 'Category',
        'blog' => 'Blog',
        'news' => 'News',
        'event' => 'Event',
        'event' => 'Event',
        'cms' => 'CMS Page',
    ],

    'unit' => [
        'kg' => 'kg',
        'gm' => 'gm',
        'ltr' => 'ltr',
        'ml' => 'ml',
        'dozen' => 'dozen',
        'set' => 'set',
        'piece' => 'piece',
        'ft' => 'ft',
        'meter' => 'meter',
        'sq.ft' => 'sq.ft',
        'sq.meter' => 'sq.meter',
        'hour' => 'hour',
        'day' => 'day',
        'km' => 'km',
        'month' => 'month',
        'year' => 'year',
    ],

    'banners' =>[
        'slider'=>'Sliders',
        'left_side_banner'=>'Left Side Banner',
        'right_side_banner'=>'Right Side Banner',
        'top_banner'=>'Top Banner',
        'bottom_banner'=>'Bottom Banner',
        'center_banner'=>'Middle Banner',




    ],

    'type' => [
        'local'=>'Local',
        'youtube'=>'Youtube',
        'vimeo'=>'Vimeo'

    ],


    'hls_type' => [
        'notes'=>'Notes',
        'Video'=>'Videos',
    ],

    'daily_learnings' => [
        '0' => 'Word Of Day',
        '1' => 'Sentence Of Day',
        '2' => 'Phrase Of Day',
    ],


    'news_type' => [
        'job_alert' => 'Job Alert',
        'admin_card' => 'Admit Card',
        'result' => 'Result',
        'article' => 'Article',
        'blogs' => 'Blog',
        'posts' => 'Posts',
    ], 

    'live_class_types' => [
        'youtube' => 'Youtube',
    ],

    // 'faculty' => 'Faculty'
    
];
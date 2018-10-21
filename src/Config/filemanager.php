<?php

return [

    'default-image' => '/assets/images/no-image.png' ,

    /*
     |--------------------------------------------------------------------------
     | Routes group config
     |--------------------------------------------------------------------------
     |
     | The default group settings for the elFinder routes.
     |
     */

    'route' => [
        'prefix' => 'laravelcity/filemanager' ,
        'middleware' => [
            'web' ,
            'auth'
        ] ,
    ] ,

    /*
    |--------------------------------------------------------------------------
    | set  post model and some fields
    |--------------------------------------------------------------------------
    |
    | for search post in editor
    |
   */

    'posts' => [
        'model' => \App\Models\Post::class ,
        'title_field' => 'title' ,
        'url_field' => 'url' ,
        'publish_date' => 'created_at' ,
    ] ,

    /*
     |--------------------------------------------------------------------------
     | upload config
     |--------------------------------------------------------------------------
     |
     | The default settings for upload
     |
     */

    'upload' => [
        'max-filesize' => [
            'image' => 1000 ,
            'file' => 1000
        ] ,
        'mimes' => [
            'image' => [ // image files
                '.png' , '.gif' , '.tiff' , '.psd' , '.jpg' , '.jpeg' ,
            ] ,
            'file' => [ // other files
                '.tgz' , '.tar' , '.zip' , '.rar' ,
                '.xlsx' , '.xls' , '.csv' , '.odt' ,
                '.txt' , '.doc' , '.docx' , '.rtf' , '.pdf' ,
                '.mp4'
            ] ,
            'both' => [ // image and files
                '.png' , '.gif' , '.tiff' , '.psd' , '.jpg' , '.jpeg' ,
                '.tgz' , '.tar' , '.zip' , '.rar' ,
                '.xlsx' , '.xls' , '.odt' , '.csv' ,
                '.txt' , '.doc' , '.docx' , '.rtf' , '.pdf' ,
                '.mkv' , '.gif' , '.avi' , '.mp4' , '.m4p' , '.m4v'

            ]
        ] ,

        // set image size
        'image-size' => [
            'thumb' => [
                'width' => 150 ,
                'height' => 150 ,
            ] ,
            'small' => [
                'width' => 300 ,
                'height' => 169 ,
            ] ,
            'medium' => [
                'width' => 493 ,
                'height' => 278 ,
            ] ,
            'large' => [
                'width' => 800 ,
                'height' => 450 ,
            ] ,
            'round' => [
                'width' => 400 ,
                'height' => 400
            ]
        ]
    ] ,

    /*
   |--------------------------------------------------------------------------
   | disk config
   |--------------------------------------------------------------------------
   |
   | The default settings for disk
   |
   */

    'disk' => 'public' ,

    /*
    |--------------------------------------------------------------------------
    | relations
    |--------------------------------------------------------------------------
    */

    'relation' => [
        'user' => [
            'class' => \App\User::class ,
            'primary_key' => 'id'
        ]
    ] ,

    /*
     |--------------------------------------------------------------------------
     | editor settings
     |--------------------------------------------------------------------------
     */

    'editor' => [
        'tinymce' => [
            'height' => 300 ,
            'content_css' => 'assets/path' ,
        ] ,
        'filemanager' => [
            'width' => 1200 ,
            'height' => 600 ,
        ]
    ]

];
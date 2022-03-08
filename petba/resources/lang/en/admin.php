<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'last_login_at' => 'Last login',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
                
            //Belongs to many relations
            'roles' => 'Roles',
                
        ],
    ],

    'vet' => [
        'title' => 'Vets',

        'actions' => [
            'index' => 'Vets',
            'create' => 'New Vet',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'phone_number' => 'Phone number',
            'address' => 'Address',
            'details' => 'Details',
            'gender' => 'Gender',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            
        ],
    ],

    'vet' => [
        'title' => 'Vets',

        'actions' => [
            'index' => 'Vets',
            'create' => 'New Vet',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'phone_number' => 'Phone number',
            'address' => 'Address',
            'details' => 'Details',
            'gender' => 'Gender',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'enabled' => 'Enabled',
            
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
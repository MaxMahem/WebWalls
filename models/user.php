<?php

class User extends AppModel {

    var $name = 'Wall';
    var $displayField = 'username';
    var $belongsTo = 'group';

    var $validate = array(
        // a user must have a unique username, no more then 255 characters long.
        'username' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Error: Username cannot be blank.',
                'required' => true,
                'last'     => true
            ),
            'maxLength' => array(
                'rule'     => array('maxLength', 127),
                'message'  => 'Error: Username may not be longer then 127 characters.',
                'required' => true,
                'last'     => true
            ),
           'isUnique' => array(
                'rule' => 'isUnique',
                'message'  => 'Error: That username is already taken.',
                'required' => true,
                'last'     => true
            )
        ),

        // a user must have a unique e-mail addresse, no more then 127 characters long.
        'email' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Error: Email cannot be blank.',
                'required' => true,
                'last'     => true
            ),
            'email' => array(
                'rule'     => 'email'
                'message'  => 'Error: Invalid e-mail address',
                'required' => true,
                'last'     => true
            ),
            'maxLength' => array(
                'rule'     => array('maxLength', 127),
                'message'  => 'Error: Email may not be longer then 127 characters.',
                'required' => true,
                'last'     => true
            ),
           'isUnique' => array(
                'rule' => 'isUnique',
                'message'  => 'Error: That email address is already taken.',
                'required' => true,
                'last'     => true
            )
        ),

        'password' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Error: Password cannot be blank.',
                'required' => true,
                'last'     => true
            )
        )
    );

}

<?php

namespace Dashboard\User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to edit an user entry
 */
class UserFormEdit extends Form
{
    /**
     * Constructor.
     *
     * @var $user \Dashboard\User\Model\User
     * @var $practices \Zend\Db\ResultSet\ResultSet
     */
    private $user;
    private $practices;

    public function __construct($user, $practices)
    {
        // Check input.
        if (!is_object($user) || empty($user))
            throw new \Exception('user is invalid');

        $this->user = $user;
        $this->practices = $practices;

        // Define form name
        parent::__construct('user-edit-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();

    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        // Add "id" readonly field
        $this->add([
            'type' => 'text',
            'name' => 'id',
            'attributes' => [
                'id' => 'id',
                'readonly' => 'readonly',
                'value' => $this->user->id
            ],
            'options' => [
                'label' => 'id',
            ],
        ]);

        // Add "user" field
        $this->add([
            'type' => 'text',
            'name' => 'user',
            'attributes' => [
                'id' => 'user',
                'value' => $this->user->user
            ],
            'options' => [
                'label' => 'User',
            ],

        ]);

        $this->add([
            'type' => 'text',
            'name' => 'email',
            'attributes' => [
                'id' => 'email',
                'value' => $this->user->email
            ],
            'options' => [
                'label' => 'E-mail',
            ],
        ]);

        // Add "password" field
        $this->add([
            'type' => 'text',
            'name' => 'password',
            'attributes' => [
                'id' => 'password',
                'value' => $this->user->password
            ],
            'options' => [
                'label' => 'Choose Password',
            ],
        ]);

        // Add "name" field
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name',
                'value' => $this->user->name
            ],
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'role',
            'attributes' => [   // this will dynamically list roles eventually
                'id' => 'role',
                'value' => $this->user->role
            ],
            'options' => [
                'label' => 'Role',
                'empty_option' => '-- Please select --',
                'value_options' => [
                    'admin' => 'Admin',
                    'practice' => 'Practice',
                    'tech' => 'Technician',
                    'marketing' => 'Marketing',
                ],
            ],
        ]);

        // Add "practice" field
        $this->add([
            'type' => 'select',
            'name' => 'pid',
            'attributes' => [
                'id' => 'pid',
                'value' => $this->user->pid
            ],
            'options' => [
                'label' => 'Practice',
                'empty_option' => '-- Please select --',
                'value_options' => $this->practices
            ],
        ]);

        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'attributes' => [],
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        // Add the save submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'savebutton',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'user',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StringToLower'],
                ['name' => 'StripNewlines'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);

        // Add input for "password" field
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],

            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

/*
        $inputFilter->add([
            'name' => 'role',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StringToLower'],
                ['name' => 'StripNewlines'],
            ],
        ]);
*/
    }
}



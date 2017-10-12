<?php

namespace Dashboard\User\Form;

use Zend\Form\Form;

/**
 * This form is used to edit an user entry
 */
class UserFormView extends Form
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
                'readonly' => 'readonly',
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
                'readonly' => 'readonly',
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
                'readonly' => 'readonly',
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
                'readonly' => 'readonly',
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
                'disabled' => 'disabled',
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
                'disabled' => 'disabled',
                'value' => $this->user->pid
            ],
            'options' => [
                'label' => 'Practice',
                'empty_option' => '-- Please select --',
                'value_options' => $this->practices
            ],
        ]);

        // Add the save submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Back',
                'id' => 'back',
            ],
        ]);
    }
}



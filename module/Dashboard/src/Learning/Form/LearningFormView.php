<?php

namespace Dashboard\Learning\Form;

use Zend\Form\Form;

/**
 * This form is used to view an Learning Centers entry
 */
class LearningFormView extends Form
{
    /**
     * Constructor.
     *
     * @var $obj \Dashboard\Learning\Model\Learning
     */
    private $obj;

    public function __construct($obj)
    {
        // Check input.
        if (!is_object($obj) || empty($obj))
            throw new \Exception('Entry is invalid');

        $this->obj = $obj;

        // Define form name
        parent::__construct('allergen-edit-form');

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
                'value' => $this->obj->id
            ],
            'options' => [
                'label' => 'id',
            ],
        ]);

        // Add "name" field
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name',
                'readonly' => 'readonly',
                'value' => $this->obj->name
            ],
            'options' => [
                'label' => 'Name',
            ],

        ]);

        $this->add([
            'type' => 'text',
            'name' => 'contact',
            'attributes' => [
                'id' => 'type',
                'readonly' => 'readonly',
                'value' => $this->obj->contact
            ],
            'options' => [
                'label' => 'Contact',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'email',
            'attributes' => [
                'id' => 'regions',
                'readonly' => 'readonly',
                'value' => $this->obj->email
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'address',
            'attributes' => [
                'id' => 'season',
                'readonly' => 'readonly',
                'value' => $this->obj->address
            ],
            'options' => [
                'label' => 'Address',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'address2',
            'attributes' => [
                'id' => 'season',
                'readonly' => 'readonly',
                'value' => $this->obj->address2
            ],
            'options' => [
                'label' => 'Address2',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'city',
            'attributes' => [
                'id' => 'city',
                'readonly' => 'readonly',
                'value' => $this->obj->city
            ],
            'options' => [
                'label' => 'City',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'state',
            'attributes' => [
                'id' => 'state',
                'readonly' => 'readonly',
                'value' => $this->obj->state
            ],
            'options' => [
                'label' => 'State',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'zip',
            'attributes' => [
                'id' => 'zip',
                'readonly' => 'readonly',
                'value' => $this->obj->zip
            ],
            'options' => [
                'label' => 'Zip',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'phone',
            'attributes' => [
                'id' => 'phone',
                'readonly' => 'readonly',
                'value' => $this->obj->phone
            ],
            'options' => [
                'label' => 'Phone',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'region',
            'attributes' => [
                'id' => 'region',
                'readonly' => 'readonly',
                'value' => $this->obj->region
            ],
            'options' => [
                'label' => 'Region',
            ],
        ]);

        // Add the back button
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


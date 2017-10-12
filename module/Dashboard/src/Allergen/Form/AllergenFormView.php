<?php

namespace Dashboard\Allergen\Form;

use Zend\Form\Form;

/**
 * This form is used to edit an user entry
 */
class AllergenFormView extends Form
{
    /**
     * Constructor.
     *
     * @var $obj \Dashboard\Allergen\Model\Allergen
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
            'name' => 'type',
            'attributes' => [
                'id' => 'type',
                'readonly' => 'readonly',
                'value' => $this->obj->type
            ],
            'options' => [
                'label' => 'Type',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'regions',
            'attributes' => [
                'id' => 'regions',
                'readonly' => 'readonly',
                'value' => $this->obj->regions
            ],
            'options' => [
                'label' => 'Regions (comma separated)',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'season',
            'attributes' => [
                'id' => 'season',
                'readonly' => 'readonly',
                'value' => $this->obj->season
            ],
            'options' => [
                'label' => 'Season',
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


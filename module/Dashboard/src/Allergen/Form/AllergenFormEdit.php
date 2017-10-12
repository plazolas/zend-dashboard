<?php

namespace Dashboard\Allergen\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to edit an user entry
 */
class AllergenFormEdit extends Form
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
                'value' => $this->obj->id,
                'readonly' => 'readonly'
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
                'value' => $this->obj->season
            ],
            'options' => [
                'label' => 'Season',
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
                'id' => 'save-button',
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
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripNewlines'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'type',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripNewlines'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'regions',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripNewlines'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'season',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripNewlines'],
            ],
        ]);
    }
}



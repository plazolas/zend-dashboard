<?php

namespace Dashboard\Learning\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to edit an user entry
 */
class LearningFormEdit extends Form
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
        parent::__construct('edit-form');

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
            'name' => 'contact',
            'attributes' => [
                'id' => 'contact',
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
                'id' => 'email',
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
                'id' => 'address',
                'value' => $this->obj->address
            ],
            'options' => [
                'label' => 'Address1',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'address2',
            'attributes' => [
                'id' => 'address2',
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
                'value' => $this->obj->region
            ],
            'options' => [
                'label' => 'Region',
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


        $inputFilter->add([
            'name' => 'contact',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
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

        $inputFilter->add([
            'name' => 'zip',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/^\d{5}([\-]?\d{4})?$/',
                        'message' => 'Invalid Zip Code'
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'region',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripNewlines'],
            ],
        ]);

    }
}



<?php
namespace Dashboard\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user registration data. This form is multi-step.
 * It determines which fields to create based on the $step argument you pass to
 * its constructor.
 */
class LoginForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('login-form');
     
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

            
            // Add "email" field
            $this->add([           
                'type'  => 'text',
                'name' => 'email',
                'attributes' => [
                    'id' => 'email'
                ],
                'options' => [
                    'label' => 'Your E-mail',
                ],
            ]);
            
            // Add "password" field
            $this->add([           
                'type'  => 'password',
                'name' => 'password',
                'attributes' => [
                    'id' => 'password'
                ],
                'options' => [
                    'label' => 'Choose Password',
                ],
            ]);

            /*  for unit test for now
        // Add the CSRF field
        $this->add([
            'type'  => 'csrf',
            'name' => 'csrf',
            'attributes' => [ 'id' => 'csrf'],
            'options' => [                
                'csrf_options' => [
                     'timeout' => 600
                ]
            ],
        ]);
            */
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'LOGIN',
                'id' => 'submitbutton',
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
                    'name'     => 'email',
                    'required' => true,
                    'filters'  => [
                        ['name' => 'StringTrim'],                    
                    ],                
                    'validators' => [
                        [
                            'name' => 'EmailAddress',
                            'options' => [
                                'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                                'useMxCheck'    => false,                            
                            ],
                        ],
                    ],
                ]);
           
        // Add input for "password" field
        $inputFilter->add([
                    'name'     => 'password',
                    'required' => true,
                    'filters'  => [                    
                    ],                
                    'validators' => [
                        [
                            'name'    => 'StringLength',
                            'options' => [
                                'min' => 6,
                                'max' => 64
                            ],
                        ],
                    ],
                ]);
        }
}



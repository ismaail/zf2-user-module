<?php
namespace User\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class LoginFilter
 * @package User\Form
 */
class LoginFilter implements InputFilterAwareInterface
{
    /**
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * @param InputFilterInterface $inputFilter
     *
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * @return InputFilter|InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            // email
            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'EmailAddress',
                        'options' => array(
                            'message' => 'invalid email address',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            )));

            // password
            $inputFilter->add($factory->createInput(array(
                'name'     => 'password',
                'required' => true,
                'validators' => array(
                    array('name'    => 'StringLength',
                          'options' => array(
                              'encoding' => 'UTF-8',
                              'min'      => 8,
                          ),
                          'break_chain_on_failure' => true,
                    ),
                ),
            )));

            // rememberMe
            $inputFilter->add($factory->createInput(array(
                'name'        => 'rememberMe',
                'allow_empty' => true,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}

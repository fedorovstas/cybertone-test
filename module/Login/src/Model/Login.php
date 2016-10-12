<?php

namespace Login\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;

/**
 * Class Login
 *
 * @package Login\Model
 */
class Login
{
  /**
   * @var string
   */
  protected $configFilePath;
  /**
   * @var
   */
  private $inputFilter;
  
  /**
   * Login constructor.
   */
  public function __construct()
  {
    $this->configFilePath = __DIR__ . '/../../config/data.csv';
  }
  
  /**
   * @param InputFilterInterface $inputFilter
   */
  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    throw new DomainException(sprintf(
      '%s does not allow injection of an alternate input filter',
      __CLASS__
    ));
  }
  
  /**
   * @return InputFilter
   */
  public function getInputFilter()
  {
    if ($this->inputFilter) {
      return $this->inputFilter;
    }
    
    $inputFilter = new InputFilter();
    
    $inputFilter->add([
      'name'     => 'login',
      'required' => TRUE,
      'filters'  => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
    ]);
    
    $inputFilter->add([
      'name'     => 'password',
      'required' => TRUE,
      'filters'  => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
    ]);
    
    $this->inputFilter = $inputFilter;
    
    return $this->inputFilter;
  }
  
  /**
   * @param $login
   * @param $password
   *
   * @return bool
   */
  public function checkLoginData($login, $password)
  {
    if (($handle = fopen($this->configFilePath, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if ($login == $data[0] && $password == $data[1]) {
          return TRUE;
        }
      }
      fclose($handle);
    }
    
    return FALSE;
  }
}
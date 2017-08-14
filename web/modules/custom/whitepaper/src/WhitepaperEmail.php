<?php

namespace Drupal\whitepaper;


class WhitepaperEmail {
  private $iAm = '';
  private $firstName = '';
  private $lastName = '';
  private $email = '';
  private $whitepaper = '';

  /**
   * WhitepaperEmail constructor.
   * @param string $iAm
   * @param string $firstName
   * @param string $lastName
   * @param string $email
   * @param $whitepaper
   */
  public function __construct($iAm, $firstName, $lastName, $email, $whitepaper) {
    $this->iAm = $iAm;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->email = $email;
    $this->whitepaper = $whitepaper;
  }

  /**
   * @return string
   */
  public function getIAm() {
    return $this->iAm;
  }

  /**
   * @param string $iAm
   */
  public function setIAm($iAm) {
    $this->iAm = $iAm;
  }

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   */
  public function setFirstName($firstName) {
    $this->firstName = $firstName;
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   */
  public function setLastName($lastName) {
    $this->lastName = $lastName;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param string $email
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * @return string
   */
  public function getWhitepaper() {
    return $this->whitepaper;
  }

  /**
   * @param string $whitepaper
   */
  public function setWhitepaper($whitepaper) {
    $this->whitepaper = $whitepaper;
  }

  public function toArray(){
    return [
      'i_am' => $this->getIAm(),
      'first_name' => $this->getFirstName(),
      'last_name' => $this->getLastName(),
      'email' => $this->getEmail(),
      'whitepaper' => $this->getWhitepaper(),
    ];
  }

}
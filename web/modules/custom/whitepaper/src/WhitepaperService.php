<?php

namespace Drupal\whitepaper;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\node\Entity\Node;

class WhitepaperService {

  /**
   * @var WhitepaperEmailsStorageInterface
   */
  protected $whitePaperEmailsStorage;

  /**
   * @var MailManagerInterface
   */
  protected $mailManager;

  /**
   * @var LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * WhitepaperService constructor.
   * @param WhitepaperEmailsStorageInterface $whitePaperEmailsStorage
   * @param MailManagerInterface $mailManager
   * @param LanguageManagerInterface $languageManager
   */
  public function __construct($whitePaperEmailsStorage, $mailManager, $languageManager) {
    $this->whitePaperEmailsStorage = $whitePaperEmailsStorage;
    $this->mailManager = $mailManager;
    $this->languageManager = $languageManager;
  }

  /**
   * @param FormStateInterface $form_state
   * @param Node $whitepaper
   * @return mixed
   */
  public function saveWhitepaperEmailFromFormSubmit($form_state, $whitepaper){
    $whitepaperEmail = new WhitepaperEmail(
      $form_state->getValue('i_am'),
      $form_state->getValue('first_name'),
      $form_state->getValue('last_name'),
      $form_state->getValue('email'),
      $whitepaper->getTitle()
    );

    if($to = \Drupal::config('whitepaper.settings')->get('whitepaper.email')){
      $this->mailManager->mail('whitepaper', 'submit', $to, $this->languageManager->getDefaultLanguage(), ['whitepaper_email' => $whitepaperEmail]);
    }

    return $this->whitePaperEmailsStorage->insert($whitepaperEmail);
  }

  /**
   * Check if the user submitted a whitepaper form
   *
   * @return bool|null
   */
  public function userSubmittedWhitepaperForm(){
    return isset($_COOKIE['Drupal_visitor_user_submitted_whitepaper_form']) && $_COOKIE['Drupal_visitor_user_submitted_whitepaper_form'] == 1;
  }

  /**
   * Flag that the user has submitted a whitepaper form
   */
  public function flagUserSubmittedWhitepaperForm(){
    $cookie = ['user_submitted_whitepaper_form' => 1];
    user_cookie_save($cookie);
  }

}
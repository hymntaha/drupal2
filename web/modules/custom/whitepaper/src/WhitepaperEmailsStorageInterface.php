<?php

namespace Drupal\whitepaper;

interface WhitepaperEmailsStorageInterface {

  /**
   * @return WhitepaperEmail[]
   */
  public function getWhitepaperEmails();

  /**
   * @param WhitepaperEmail $whitepaperEmail
   * @return mixed
   */
  public function insert($whitepaperEmail);
}
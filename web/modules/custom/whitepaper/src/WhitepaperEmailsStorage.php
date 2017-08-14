<?php

namespace Drupal\whitepaper;

use Drupal\Core\Database\Connection;

class WhitepaperEmailsStorage implements WhitepaperEmailsStorageInterface{

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function getWhitepaperEmails(){
    $whitepaperEmails = [];

    $result = $this->connection
      ->select('whitepaper_emails', 'w')
      ->fields('w')
      ->execute();

    if(!empty($result)){
      foreach($result as $row){
        $whitepaperEmails[] = new WhitepaperEmail($row->i_am, $row->first_name, $row->last_name, $row->email, $row->whitepaper);
      }
    }

    return $whitepaperEmails;
  }

  /**
   * @param  WhitepaperEmail $whitepaperEmail
   * @return \Drupal\Core\Database\StatementInterface|int|null
   */
  public function insert($whitepaperEmail) {
    return $this->connection
      ->insert('whitepaper_emails')
      ->fields($whitepaperEmail->toArray())
      ->execute();
  }
}
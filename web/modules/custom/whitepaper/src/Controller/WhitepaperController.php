<?php

namespace Drupal\whitepaper\Controller;

use Drupal\avatar_utils\AvatarUtilsCSVDataExport;
use Drupal\Core\Controller\ControllerBase;
use Drupal\whitepaper\WhitepaperEmailsStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WhitepaperController extends ControllerBase{

  /**
   * @var WhitepaperEmailsStorageInterface
   */
  protected $whitepaperEmailsStorage;

  /**
   * @var AvatarUtilsCSVDataExport
   */
  protected $avatarUtilsCSVDataExport;

  /**
   * WhitepaperController constructor.
   * @param $whitepaperEmailsStorage
   * @param $avatarUtilsCSVDataExport
   */
  public function __construct($whitepaperEmailsStorage, $avatarUtilsCSVDataExport) {
    $this->whitepaperEmailsStorage = $whitepaperEmailsStorage;
    $this->avatarUtilsCSVDataExport = $avatarUtilsCSVDataExport;
  }


  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('whitepaper.emails.storage'),
      $container->get('avatar.utils.csv.data.export')
    );
  }

  public function whitepaperAdminEmailDownload() {
    $headers = ['I am a', 'First Name', 'Last Name', 'Email', 'Whitepaper'];
    $rows = [];
    $filename = 'whitepaper_emails_download';

    foreach ($this->whitepaperEmailsStorage->getWhitepaperEmails() as $whitepaperEmail) {
      $rows[] = $whitepaperEmail->toArray();
    }

    return $this->avatarUtilsCSVDataExport->dataExportCSV($headers, $rows, $filename);
  }

}
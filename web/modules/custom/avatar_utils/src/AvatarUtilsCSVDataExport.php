<?php

namespace Drupal\avatar_utils;

use Symfony\Component\HttpFoundation\Response;

class AvatarUtilsCSVDataExport {
  public function dataExportCSV($headers = [], $rows, $filename = 'export'){
    $response = new Response();

    $fh = fopen('php://output', 'w');

    if($headers){
      fputcsv($fh, $headers);
    }

    foreach($rows as $row){
      fputcsv($fh, $row);
    }

    fclose($fh);

    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'.csv"');

    return $response;
  }

}
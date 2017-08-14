<?php

namespace Drupal\training\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'training_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "training_field_formatter",
 *   label = @Translation("Training"),
 *   field_types = {
 *     "training_field_type"
 *   }
 * )
 */
class TrainingFieldFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      // Implement default settings.
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return array(
      // Implement settings form.
    ) + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $date = new DrupalDateTime($item->date);
      $start_time = new DrupalDateTime($item->start_time);
      $end_time = new DrupalDateTime($item->end_time);

      $elements[$delta] = [
        '#theme' => 'training_item',
        '#date' => $date->format('l, F jS, Y'),
        '#start_time' => $start_time->format('g:i'),
        '#end_time' => $end_time->format('g:ia T'),
        '#url' => $item->url,
      ];
    }

    return $elements;
  }

}

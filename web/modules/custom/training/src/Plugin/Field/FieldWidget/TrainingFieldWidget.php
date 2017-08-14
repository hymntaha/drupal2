<?php

namespace Drupal\training\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'training_default' widget.
 *
 * @FieldWidget(
 *   id = "training_default",
 *   label = @Translation("Training"),
 *   field_types = {
 *     "training_field_type"
 *   }
 * )
 */
class TrainingFieldWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The date format storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $dateStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityStorageInterface $date_storage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);

    $this->dateStorage = $date_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')->getStorage('date_format')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $date_type = 'date';
    $time_type = 'none';
    $date_format = $this->dateStorage->load('html_date')->getPattern();
    $time_format = '';

    $element['date'] = array(
      '#type' => 'datetime',
      '#default_value' => NULL,
      '#date_increment' => 1,
      '#date_timezone' => drupal_get_user_timezone(),
      '#required' => $element['#required'],
      '#date_date_format' => $date_format,
      '#date_date_element' => $date_type,
      '#date_date_callbacks' => array(),
      '#date_time_format' => $time_format,
      '#date_time_element' => $time_type,
      '#date_time_callbacks' => array(),
      '#title' => $this->t('Date'),
    );

    if ($items[$delta]->date) {
      $date = $items[$delta]->date;
      if(!$date instanceof DrupalDateTime){
        $date = new DrupalDateTime($date);
      }
      // The date was created and verified during field_load(), so it is safe to
      // use without further inspection.
      if ($this->getFieldSetting('datetime_type') == DateTimeItem::DATETIME_TYPE_DATE) {
        // A date without time will pick up the current time, use the default
        // time.
        datetime_date_default_time($date);
      }
      $date->setTimezone(new \DateTimeZone($element['date']['#date_timezone']));
      $element['date']['#default_value'] = $date;
    }

    $date_type = 'none';
    $time_type = 'time';
    $time_format = $this->dateStorage->load('html_time')->getPattern();

    $element['start_time'] = array(
      '#type' => 'datetime',
      '#default_value' => NULL,
      '#date_increment' => 1,
      '#date_timezone' => drupal_get_user_timezone(),
      '#required' => $element['#required'],
      '#date_date_format' => $date_format,
      '#date_date_element' => $date_type,
      '#date_date_callbacks' => array(),
      '#date_time_format' => $time_format,
      '#date_time_element' => $time_type,
      '#date_time_callbacks' => array(),
      '#title' => $this->t('Start Time'),
    );

    $element['end_time'] = array(
      '#type' => 'datetime',
      '#default_value' => NULL,
      '#date_increment' => 1,
      '#date_timezone' => drupal_get_user_timezone(),
      '#required' => $element['#required'],
      '#date_date_format' => $date_format,
      '#date_date_element' => $date_type,
      '#date_date_callbacks' => array(),
      '#date_time_format' => $time_format,
      '#date_time_element' => $time_type,
      '#date_time_callbacks' => array(),
      '#title' => $this->t('End Time'),
    );

    if ($items[$delta]->start_time) {
      $date = $items[$delta]->start_time;
      if(!$date instanceof DrupalDateTime){
        $date = new DrupalDateTime($date);
      }
      // The date was created and verified during field_load(), so it is safe to
      // use without further inspection.
      if ($this->getFieldSetting('datetime_type') == DateTimeItem::DATETIME_TYPE_DATE) {
        // A date without time will pick up the current time, use the default
        // time.
        datetime_date_default_time($date);
      }
      $date->setTimezone(new \DateTimeZone($element['start_time']['#date_timezone']));
      $element['start_time']['#default_value'] = $date;
    }

    if ($items[$delta]->end_time) {
      $date = $items[$delta]->end_time;
      if(!$date instanceof DrupalDateTime){
        $date = new DrupalDateTime($date);
      }
      // The date was created and verified during field_load(), so it is safe to
      // use without further inspection.
      if ($this->getFieldSetting('datetime_type') == DateTimeItem::DATETIME_TYPE_DATE) {
        // A date without time will pick up the current time, use the default
        // time.
        datetime_date_default_time($date);
      }
      $date->setTimezone(new \DateTimeZone($element['end_time']['#date_timezone']));
      $element['end_time']['#default_value'] = $date;
    }

    $element['url'] = array(
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->url) ? $items[$delta]->url : NULL,
      '#title' => $this->t('URL'),
    );

    return $element;
  }

  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // The widget form element type has transformed the value to a
    // DrupalDateTime object at this point. We need to convert it back to the
    // storage timezone and format.
    foreach ($values as &$item) {
      if (!empty($item['date']) && $item['date'] instanceof DrupalDateTime) {
        $date = $item['date'];
        switch ($this->getFieldSetting('datetime_type')) {
          case DateTimeItem::DATETIME_TYPE_DATE:
            // If this is a date-only field, set it to the default time so the
            // timezone conversion can be reversed.
            datetime_date_default_time($date);
            $format = DATETIME_DATE_STORAGE_FORMAT;
            break;

          default:
            $format = DATETIME_DATETIME_STORAGE_FORMAT;
            break;
        }
        // Adjust the date for storage.
        $date->setTimezone(new \DateTimezone(DATETIME_STORAGE_TIMEZONE));
        $item['date'] = $date->format($format);
      }
      if (!empty($item['start_time']) && $item['start_time'] instanceof DrupalDateTime) {
        $date = $item['start_time'];
        $item['start_time'] = $date->format(DATETIME_DATETIME_STORAGE_FORMAT);
      }
      if (!empty($item['end_time']) && $item['end_time'] instanceof DrupalDateTime) {
        $date = $item['end_time'];
        $item['end_time'] = $date->format(DATETIME_DATETIME_STORAGE_FORMAT);
      }
    }
    return $values;
  }

}

<?php

namespace Drupal\training\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'training_field_type' field type.
 *
 * @FieldType(
 *   id = "training_field_type",
 *   label = @Translation("Training"),
 *   description = @Translation("Training"),
 *   default_widget = "training_default",
 *   default_formatter = "training_field_formatter"
 * )
 */
class TrainingFieldType extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return array(
      'max_length' => 255,
      'is_ascii' => TRUE,
      'case_sensitive' => FALSE,
    ) + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['date'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(new TranslatableMarkup('Date'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    $properties['start_time'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(new TranslatableMarkup('Start Time'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    $properties['end_time'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(new TranslatableMarkup('End Time'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    $properties['url'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('URL'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $schema = array(
      'columns' => array(
        'date' => array(
          'type' => 'varchar',
          'length' => (int) $field_definition->getSetting('max_length'),
          'not null' => true,
        ),
        'start_time' => array(
          'type' => 'varchar_ascii',
          'length' => (int) $field_definition->getSetting('max_length'),
          'not null' => true,
        ),
        'end_time' => array(
          'type' => 'varchar_ascii',
          'length' => (int) $field_definition->getSetting('max_length'),
          'not null' => true,
        ),
        'url' => array(
          'type' => 'varchar_ascii',
          'length' => (int) $field_definition->getSetting('max_length'),
          'not null' => true,
        ),
      ),
    );

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    if ($max_length = $this->getSetting('max_length')) {
      $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
      $constraints[] = $constraint_manager->create('ComplexData', array(
        'url' => array(
          'Length' => array(
            'max' => $max_length,
            'maxMessage' => t('%name: may not be longer than @max characters.', array(
              '%name' => $this->getFieldDefinition()->getLabel(),
              '@max' => $max_length
            )),
          ),
        ),
      ));
    }

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $timestamp = REQUEST_TIME - mt_rand(0, 86400 * 365);
    $values['date'] = gmdate(DATETIME_DATE_STORAGE_FORMAT, $timestamp);

    $values['start_time'] = '3:00PM';
    $values['end_time'] = '4:00PM';

    $values['url'] = 'http:/www.google.com';

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $elements = [];

    $elements['max_length'] = array(
      '#type' => 'number',
      '#title' => t('Maximum length'),
      '#default_value' => $this->getSetting('max_length'),
      '#required' => TRUE,
      '#description' => t('The maximum length of the field in characters.'),
      '#min' => 1,
      '#disabled' => $has_data,
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('date')->getValue();
    return $value === NULL || $value === '';
  }

}

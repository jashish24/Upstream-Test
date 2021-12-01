<?php

namespace Drupal\ge_user_info_import\Plugin\migrate\destination;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\migrate\Row;
use Drupal\Core\Database\Connection;

/**
 * User info destination plugin.
 *
 * @MigrateDestination(
 *   id = "ge_user_info"
 * )
 */
class GeUserInfo extends DestinationBase implements ContainerFactoryPluginInterface {

  /**
   * Database Service Object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, Connection $connection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
    $this->supportsRollback = TRUE;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['info_id']['type'] = 'integer';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function fields(MigrationInterface $migration = NULL) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function import(Row $row, array $old_destination_id_values = []) {
    $info_id = $row->getSourceProperty('info_id');
    $first_name = $row->getSourceProperty('first_name');
    $last_name = $row->getSourceProperty('last_name');
    $full_name = "{$first_name} {$last_name}";

    $exists = isset($old_destination_id_values[0]) ? $old_destination_id_values[0] : NULL;

    // For migration update option.
    if ($exists) {
      $this->connection->update('ge_user_info')
        ->fields([
          'user_full_name' => $full_name,
        ])
        ->condition('info_id', $info_id)
        ->execute();

      $ids[] = $exists;
      return $ids;
    }

    $info_id = $this->connection->insert('ge_user_info')
      ->fields([
        'info_id' => $info_id,
        'user_full_name' => $full_name,
      ])
      ->execute();

    $ids[] = $info_id;

    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function rollback(array $destination_identifier) {
    $this->connection->delete('ge_user_info')
      ->condition('info_id', $destination_identifier['info_id'])
      ->execute();
  }

}

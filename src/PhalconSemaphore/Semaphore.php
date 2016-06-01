<?php
namespace PhalconSemaphore;

use Phalcon\Mvc\User\Component;

/**
 * PhalconSemaphore
 */
class Semaphore extends Component
{

  public $table = '';

  /**
   * Constructor
   *
   * @param string $table
   */
  public function __construct($table = 'semaphore')
  {

    $this->table = $table;

  }


  /**
   * Execute operation
   *
   * @param string $class
   * @param string $method
   * @param int $hours
   * @param array $args
   */
  public function run($class, $method, $hours = 24, $args = array())
  {

    $name = "$class::$method";

    if (class_exists($class)) {

      try {

        // If this semaphore has been run in the past $hours hours, return
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE name = ? AND timestamp >= DATE_SUB(NOW(), INTERVAL ? hour)';
        if ($existing = $this->pdo->executeQuery($sql, array($name, $hours))) {
          return;
        }

        // Perform operation
        $results = call_user_func_array(array($class, $method), $args);

        // Insert or update timestamp
        $sql = 'INSERT INTO ' . $this->table . ' (`name`, `timestamp`) VALUES (?, NOW()) ON DUPLICATE KEY UPDATE timestamp = NOW()';
        $this->pdo->executeQuery($sql, array($name));

      } catch (Exception $e) {
        // Error
      }

    }

  }


  /**
   * Execute operation by instantiation
   *
   * @param string $class
   * @param string $method
   * @param int $hours
   * @param array $args
   */
  public function runInstantiate($class, $method, $hours = 24, $args = array())
  {

    $name = "$class::$method";

    if (class_exists($class)) {

      try {

        // If this semaphore has been run in the past $hours hours, return
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE name = ? AND timestamp >= DATE_SUB(NOW(), INTERVAL ? hour)';
        if ($existing = $this->pdo->executeQuery($sql, array($name, $hours))) {
          return;
        }

        // Perform operation
        $obj = new $class();
        $results = $obj->$method($args);

        // Insert or update timestamp
        $sql = 'INSERT INTO ' . $this->table . ' (`name`, `timestamp`) VALUES (?, NOW()) ON DUPLICATE KEY UPDATE timestamp = NOW()';
        $this->pdo->executeQuery($sql, array($name));

      } catch (Exception $e) {
        // Error
      }

    }

  }

}

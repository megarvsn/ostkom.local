<?php
/*
  Пример использования

  if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-robots.php')) {
    include_once ($_SERVER['DOCUMENT_ROOT'] . '/d-robots.php');
    $dRobots = dRobots::fromFile();
    $noindex = $dRobots->checkUrl($_SERVER['REQUEST_URI']) ? '<meta name="googlebot" content="noindex">' . PHP_EOL : '';
  } else $noindex = '';

  //Вывод в шаблоне
  echo $noindex;

 */

class dRobots {
    private $rules = array();

    /**
     * Читает содержимое robots.txt и возвращает dRobots на основе его содержимого.
     *
     * @static
     * @param string $file Путь к robots.txt (по умолчанию $_SERVER['DOCUMENT_ROOT'] . '/robots.txt')
     * @return dRobots
     */
    public static function fromFile($file = false) {
        if (!$file || !is_file($file) || !is_readable($file)) $file = $_SERVER['DOCUMENT_ROOT'] . '/robots.txt';
        return new dRobots(file_get_contents($file));
    }

    /**
     * @param string $robots Содержимое robots.txt
     */
    public function __construct($robots) {
        $lines = explode("\n", $robots);
        $matches = array();
        foreach ($lines as $line) {
            if (preg_match('{Disallow:(.+?)(\#|$)}', $line, $match)) {
                $this->rules[] = '{^' . str_replace(array('.', '*', '?'), array('\.', '.*', '\?'), trim($match[1])) . '}';
            }
        }
    }

    /**
     * Проверяет, запрещен ли адрес в robots.txt
     *
     * @param string $url Адрес для проверки (без http://host)
     * @return bool True если адрес запрещен в robots.txt
     */
    public function checkUrl($url) {
        foreach ($this->rules as $rule) {
            if (preg_match($rule, $url)) return true;
        }
        return false;
    }
}
<?
spl_autoload_register(function ($class_name) {
    $file = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/' . str_replace('\\', '/', $class_name) . '.class.php';
    if (file_exists($file)) require_once $file;
});

if (file_exists(__DIR__ .'/redirect.php')) {
    include_once(__DIR__ . '/redirect.php');
}
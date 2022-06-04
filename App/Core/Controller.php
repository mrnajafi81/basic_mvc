<?php namespace App\Core;

use Twig\{Loader\FilesystemLoader, Environment};
use App\Core\Database;
use PDO;

class Controller
{
    private $twig;
    private $pdo;

    public function __construct()
    {
        $loader = new FilesystemLoader(VIEWS_PATH);
        $this->twig = new Environment($loader);
        $this->connectToDatabase();
    }

    public function loadView($view, $data = [])
    {
        if (file_exists(VIEWS_PATH . DS . $view . 'View.twig')) {
            echo $this->twig->render($view . "View.twig", $data);
        } else {
            die("View Not Found");
        }
    }

    public function loadModel($model) : object
    {
        if (file_exists(MODELS_PATH . DS . $model . 'Model.php')) {
            $model = "App\Models\\" . $model . 'Model';
            return new $model($this->pdo);
        } else {
            die("Model Not Found");
        }
    }

    public function connectToDatabase()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }
}
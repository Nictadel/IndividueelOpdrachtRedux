<?php
/**
 * Created by PhpStorm.
 * User: jornevanhelvert
 * Date: 18/09/2018
 * Time: 10:51
 */
namespace App\Model;

use \PDO;

class Connection
{
    private $pdo;

    public function __construct($connectionString, $user = null, $password = null)
    {
        $this->pdo = new PDO($connectionString, $user, $password);
        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    /**
     * @return mixed
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}

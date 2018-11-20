<?php
/**
 * Created by PhpStorm.
 * User: jornevanhelvert
 * Date: 21/09/2018
 * Time: 10:08
 */

namespace App\Tests\Model;

use App\Model\Connection;
use PHPUnit\Framework\TestCase;
use App\Model\PDOReactionModel;

class PDOReactionModelTest extends TestCase
{
    private $connection;

    public function setUp()
    {
        $this->connection = new Connection('sqlite:memory');
        $this->pdo = $this->connection->getPdo();
        $this->pdo->exec('DROP TABLE IF EXISTS reactions');
        $this->pdo->exec('CREATE TABLE reactions (
                        ID INT, 
                        MessageID INT,
                        Content VARCHAR(255), 
                        PRIMARY KEY (ID)
                   )');
    }

    public function tearDown()
    {
        $this->connection = null;
    }

    public function testPlaceReaction_validReaction_token()
    {
        $reactionModel = new PDOReactionModel($this->connection);
        $reaction = 'reaction';
        $id = 1;
        $token = $reactionModel->placeReaction($reaction, $id);
        $this->assertEquals($token, bin2hex($reaction.$id));
    }

    /**
     * @expectedException \InvalidArgumentException
     **/
    public function testPlaceReaction_invalidReaction_invalidArgumentException()
    {
        $reactionModel = new PDOReactionModel($this->connection);
        $reaction = 1;
        $id = 'reaction';
        $reactionModel->placeReaction($reaction, $id);
    }

    /**
     * @expectedException \PDOException
     **/
    public function testPlaceReaction_invalidRequest_pdoException()
    {
        $reactionModel = new PDOReactionModel($this->connection);

        $this->pdo->exec('DROP TABLE IF EXISTS reactions');

        $id = 1;
        $reaction = 'reaction';
        $reactionModel->placeReaction($reaction, $id);
    }
}

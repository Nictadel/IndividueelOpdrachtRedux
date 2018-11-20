<?php
/**
 * Created by PhpStorm.
 * User: Ferre
 * Date: 22/09/2018
 * Time: 15:01
 */

namespace App\Tests\Model;

use App\Model\Connection;
use PHPUnit\Framework\TestCase;
use App\Model\PDOMessageModel;

class PDOMessageModelTest extends TestCase
{
    private $connection;
    private $pdo;

    public function setUp()
    {
        $this->connection = new Connection('sqlite:memory');
        $this->pdo = $this->connection->getPdo();
        $this->pdo->exec('DROP TABLE IF EXISTS messages');
        $this->pdo->exec('CREATE TABLE messages (
                        ID INT,
                        Content VARCHAR(255),
                        CategoryID INT,
                        UpVotes INT,
                        DownVotes INT,
                        PRIMARY KEY (ID)
                        )');
        $this->pdo->exec('INSERT INTO `messages` (`ID`, `Content`, `CategoryID`, `UpVotes`, `DownVotes`) VALUES
                          (1, \'Dit is dummy data.\', 1, 0, 0),
                          (2, \'Dit is ook dummy data.\', 1, 0, 0);');
    }

    public function tearDown()
    {
        $this->connection = null;
        $this->pdo = null;
    }


    public function testGetAllMessages_messages()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 1, 'Content' => 'Dit is dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals($messages, $messageModel->getAllMessages());
    }

    /**
     * @expectedException \PDOException
     **/
    public function testGetAllMessages_pdoError_pdoException()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->pdo->exec('DROP TABLE IF EXISTS messages');

        $messageModel->getMessageById(1);
    }

    public function testGetMessageByPartialContent_validKeyword_multipleMessages()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 1, 'Content' => 'Dit is dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals($messages, $messageModel->getMessageByPartialContent('dummy'));
    }

    public function testGetMessageByPartialContent_validKeyword_message()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals($messages, $messageModel->getMessageByPartialContent('ook'));
    }

    public function testGetMessageByPartialContent_invalidKeyword_null()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $this->assertnull($messageModel->getMessageByPartialContent('koekje'));
    }

    /**
     * @expectedException \PDOException
     **/
    public function testGetGetMessageByPartialContent_pdoError_pdoException()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->pdo->exec('DROP TABLE IF EXISTS messages');

        $messageModel->getMessageByPartialContent('dummy');
    }

    public function testGetMessageByPartialContentAndCategoryId_validKeywordValidId_multipleMessages()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 1, 'Content' => 'Dit is dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals($messages, $messageModel->getMessageByPartialContentAndCategoryId('dummy', 1));
    }



    public function testGetMessageByPartialContentAndCategoryId_validKeywordValidId_message()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals($messages, $messageModel->getMessageByPartialContentAndCategoryId('ook', 1));
    }

    public function testGetMessageByPartialContentAndCategoryId_validKeywordInvalidId_null()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->assertNull($messageModel->getMessageByPartialContentAndCategoryId('dummy', 3));
    }

    public function testGetMessageByPartialContentAndCategoryId_invalidKeywordValidId_null()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->assertNull($messageModel->getMessageByPartialContentAndCategoryId('koekje', 1));
    }

    public function testGetMessageByPartialContentAndCategoryId_invalidKeywordInvalidId_null()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->assertNull($messageModel->getMessageByPartialContentAndCategoryId('koekje', 3));
    }

    /**
     * @expectedException \PDOException
     **/
    public function testGetMessageByPartialContentAndCategoryId_pdoError_pdoException()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->pdo->exec('DROP TABLE IF EXISTS messages');

        $messageModel->getMessageByPartialContentAndCategoryId('dummy', 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     **/
    public function testGetMessageByPartialContentAndCategoryId_idIsNoyAnInteger_invalidArgumentException()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $messageModel->getMessageByPartialContentAndCategoryId('dummy', 'dummy');
    }


    public function testUpvoteMessageByMessageId_validId_strOK()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 1, 'Content' => 'Dit is dummy data.', 'CategoryId' => 1,
            'Upvotes' => 1, 'Downvotes' => 0];
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals("OK", $messageModel->upvoteMessageByMessageId(1));
        $this->assertEquals($messages[0]['Upvotes'], $messageModel->getMessageById(1)['Upvotes']);
    }

    /**
     * @expectedException \PDOException
     **/
    public function testUpvoteMessageByMessageId_pdoError_pdoException()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->pdo->exec('DROP TABLE IF EXISTS messages');

        $messageModel->upvoteMessageByMessageId(1);
    }

    public function testDownvoteMessageByMessageId_validId_strOK()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $messages = array();
        $messages[] = ['Id' => 1, 'Content' => 'Dit is dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 1];
        $messages[] = ['Id' => 2, 'Content' => 'Dit is ook dummy data.', 'CategoryId' => 1,
            'Upvotes' => 0, 'Downvotes' => 0];

        $this->assertEquals("OK", $messageModel->downvoteMessageByMessageId(1));
        $this->assertEquals($messages[0]['Downvotes'], $messageModel->getMessageById(1)['Downvotes']);
    }

    /**
     * @expectedException \PDOException
     **/
    public function testDownvoteMessageByMessageId_pdoError_pdoException()
    {
        $messageModel = new PDOMessageModel($this->connection);

        $this->pdo->exec('DROP TABLE IF EXISTS messages');

        $messageModel->downvoteMessageByMessageId(1);
    }
}

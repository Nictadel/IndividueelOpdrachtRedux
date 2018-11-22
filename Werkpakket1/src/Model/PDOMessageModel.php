<?php
/**
 * Created by PhpStorm.
 * User: jornevanhelvert
 * Date: 18/09/2018
 * Time: 11:42
 */

namespace App\Model;

use http\Exception\InvalidArgumentException;

class PDOMessageModel implements MessageModel
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAllMessages()
    {
        $pdo = $this->connection->getPdo();

        $statement = $pdo->prepare('SELECT * FROM messages');
        $messages = null;
        $statement->execute();

        $databaseData = $statement->fetchAll();
        for ($i = 0; $i < count($databaseData); $i++) {
            $message = $databaseData[$i];

            if ($message !== null) {
                $messages[] = ['Id' => intval($message[0]), 'Content' => $message[1], 'Category' => $message[2],
                    'Upvotes' => $message[3], 'Downvotes' => $message[4], 'DateTime' => $message[5]];
            }
        }

        return $messages;
    }

    public function getMessageById($id)
    {
        $pdo = $this->connection->getPdo();

        $id = null;
        $content = null;
        $category = null;
        $upvotes = null;
        $downvotes = null;
        $datetime = null;


        $statement = $pdo->prepare('SELECT * FROM messages WHERE ID=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        $statement->execute();
        $statement->bindColumn(1, $id, \PDO::PARAM_INT);
        $statement->bindColumn(2, $content, \PDO::PARAM_STR);
        $statement->bindColumn(3, $category, \PDO::PARAM_STR);
        $statement->bindColumn(4, $upvotes, \PDO::PARAM_INT);
        $statement->bindColumn(5, $downvotes, \PDO::PARAM_INT);
        $statement->bindColumn(6, $datetime, \PDO::PARAM_STR);

        $message = null;

        if ($statement->fetch(\PDO::FETCH_BOUND)) {
            $message = ['Id' => $id, 'Content' => $content, 'Category' => $category,
                'Upvotes' => $upvotes, 'Downvotes' => $downvotes, 'Datetime' => $datetime];
        }

        return $message;
    }

    public function getMessageByPartialContent($keywords)
    {
        $pdo = $this->connection->getPdo();

        $statement = $pdo->prepare('SELECT * FROM messages WHERE Content LIKE ?');
        $statement->bindValue(1, "%$keywords%", \PDO::PARAM_STR);

        $messages = null;

        $statement->execute();

        $databaseData = $statement->fetchAll();

        for ($i = 0; $i < count($databaseData); $i++) {
            $message = $databaseData[$i];

            if ($message !== null) {
                $messages[] = ['Id' => $message[0], 'Content' => $message[1], 'CategoryId' => $message[2],
                    'Upvotes' => $message[3], 'Downvotes' => $message[4], 'DateTime' => $message[5]];
            }
        }

        return $messages;
    }

    public function getMessageByPartialContentAndCategory($keywords, $name)
    {
        $pdo = $this->connection->getPdo();

        if (gettype($name) !== 'string') {
            throw new \InvalidArgumentException();
        }

        $statement = $pdo->prepare('SELECT * FROM messages WHERE Content LIKE ? AND Category LIKE ?');
        $statement->bindValue(1, "%$keywords%", \PDO::PARAM_STR);
        $statement->bindValue(2, $name, \PDO::PARAM_INT);

        $messages = null;

        $statement->execute();

        $databaseData = $statement->fetchAll();

        for ($i = 0; $i < count($databaseData); $i++) {
            $message = $databaseData[$i];

            if ($message !== null) {
                $messages[] = ['Id' => $message[0], 'Content' => $message[1], 'Category' => $message[2],
                    'Upvotes' => $message[3], 'Downvotes' => $message[4], 'DateTime' => $message[5]];
            }
        }

        return $messages;
    }

    public function getMessageByCategory($name)
    {
        $pdo = $this->connection->getPdo();

        if (gettype($name) !== 'string') {
            throw new \InvalidArgumentException();
        }

        $statement = $pdo->prepare('SELECT * FROM messages WHERE Category = ?');
        $statement->bindValue(1, "$name", \PDO::PARAM_STR);

        $messages = null;

        $statement->execute();

        $databaseData = $statement->fetchAll();

        for ($i = 0; $i < count($databaseData); $i++) {
            $message = $databaseData[$i];

            if ($message !== null) {
                $messages[] = ['Id' => $message[0], 'Content' => $message[1], 'Category' => $message[2],
                    'Upvotes' => $message[3], 'Downvotes' => $message[4], 'DateTime' => $message[5]];
            }
        }

        return $messages;
    }



    public function upvoteMessageByMessageId($messageId)
    {
        $pdo = $this->connection->getPdo();
        $message = $this->getMessageById($messageId);
        $message["Upvotes"] = (int) $message["Upvotes"] + 1;

        $statement = $pdo->prepare('UPDATE messages SET Upvotes = ? WHERE Id = ?');
        $statement->bindValue(1, $message["Upvotes"], \PDO::PARAM_INT);
        $statement->bindValue(2, $messageId, \PDO::PARAM_INT);

        $statement->execute();

        $messages = $this->getAllMessages();

        return $messages;
    }

    public function downvoteMessageByMessageId($messageId)
    {
        $pdo = $this->connection->getPdo();
        $message = $this->getMessageById($messageId);
        $message["Downvotes"] = (int) $message["Downvotes"] + 1;

        $statement = $pdo->prepare('UPDATE messages SET Downvotes = ? WHERE Id = ?');
        $statement->bindValue(1, $message["Downvotes"], \PDO::PARAM_INT);
        $statement->bindValue(2, $messageId, \PDO::PARAM_INT);

        $statement->execute();

        $messages = $this->getAllMessages();

        return $messages;
    }

    public function deleteMessageById($messageId){
        $pdo = $this->connection->getPdo();
        $statement = $pdo->prepare('DELETE FROM messages WHERE Id = ?');
        $statement->bindValue(1, $messageId, \PDO::PARAM_INT);
        $statement->execute();

    }

}

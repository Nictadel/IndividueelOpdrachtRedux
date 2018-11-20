<?php
/**
 * Created by PhpStorm.
 * User: jornevanhelvert
 * Date: 20/09/2018
 * Time: 12:11
 */

namespace App\Model;

use Sirius\Validation\Helper;

class PDOReactionModel implements ReactionModel
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function placeReaction($parameters)
    {
        $pdo = $this->connection->getPdo();

        if ($this->checkTypes($parameters)) {
            throw new \InvalidArgumentException();
        }




        $reaction = $parameters['reaction'];
        $messageId = $parameters['messageId'];


        //INPUT VALIDATION VIA SIRIUSPHP-VALIDATION
        if(!Helper::alphanumhyphen($reaction)){
            throw new \InvalidArgumentException("The reaction contains illegal characters");
        }
        $statement = $pdo->prepare('INSERT INTO reactions (MessageID, Content) VALUES (?, ?)');
        $statement->bindValue(1, $messageId, \PDO::PARAM_INT);
        $statement->bindValue(2, $reaction, \PDO::PARAM_STR);

        $statement->execute();
        return bin2hex($reaction.$messageId);



    }

    private function checkTypes($parameters) {
       return $parameters === null;
    }


    public function deleteReactionById($reactionId){
        $pdo = $this->connection->getPdo();
        $statement = $pdo->prepare('DELETE FROM reactions WHERE Id = ?');
        $statement->bindValue(1, $reactionId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function getAllReactionsByMessageId($messageId){
        $pdo = $this->connection->getPdo();

        $statement = $pdo->prepare('SELECT * FROM reactions WHERE MessageID = ? ');
        $statement->bindValue(1, $messageId, \PDO::PARAM_STR);
        $statement->execute();
        $dbData = $statement->fetchAll();
        $reactions = null;

        for ($i = 0; $i < count($dbData); $i++) {
            $reaction = $dbData[$i];

            if ($reaction !== null) {
                $reactions[] = ['Id' => $reaction[0], 'messageId' => $reaction[1], 'Content' => $reaction[2]];
            }
        }

        return $reactions;

    }


    public function getAllReactions(){
        $pdo = $this->connection->getPdo();

        $statement = $pdo->prepare('SELECT * FROM reactions');
        $statement->execute();
        $dbData = $statement->fetchAll();
        $reactions = null;

        for ($i = 0; $i < count($dbData); $i++) {
            $reaction = $dbData[$i];
            if ($reaction !== null) {
                $reactions[] = ['Id' => $reaction[0], 'messageId' => $reaction[1], 'Content' => $reaction[2]];
            }
        }

        return $reactions;

    }





}

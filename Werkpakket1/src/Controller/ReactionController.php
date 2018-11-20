<?php

namespace App\Controller;

use App\Model\PDOReactionModel;
use App\Model\ReactionModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReactionController extends AbstractController
{
    private $reactionModel;

    public function __construct(PDOReactionModel $reactionModel)
    {
        $this->reactionModel = $reactionModel;
    }

    /**
     * @Route("/reaction", methods={"POST"}, name="placeReactionOnMessageId")
     */
    public function placeReactionOnMessageId(Request $request)
    {
        $statuscode = 200;
        $response = null;
        $parameters = json_decode($request->getContent(), true);

        try {
            $response = $this->reactionModel->placeReaction($parameters);
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
            $response = $exception->getMessage();
        } catch (\PDOException $exception) {
            $statuscode = 500;
            var_dump($exception);
        }

        return new JsonResponse($response, $statuscode);
    }



    /**
     * @Route("/reactions", methods={"GET"}, name="getAllMessages")
     */
    public function getReactions(Request $request)
    {
        $statuscode = 200;
        $reactions[] = null;
        $messageId = $request->query->get('messageId');

        try {
            if($messageId){
                $reactions = $this->reactionModel->getAllReactionsByMessageId($messageId);
            }else {
                $reactions = $this->reactionModel->getAllReactions();
            }

            if ($reactions === null) {
                $statuscode = 404;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }
        return new JsonResponse($reactions, $statuscode);
    }


    /**
     * @Route("/reaction", methods={"DELETE"}, name="deleteReaction")
     */
    public function deleteReaction(Request $request)
    {
        $statuscode = 200;
        $reactions[] = null;
        $reactionId = $request->query->get('reactionId');
        if($reactionId)
        {
            try {
                $this->reactionModel->deleteReactionById($reactionId);
            } catch (\InvalidArgumentException $exception) {
                $statuscode = 400;
            } catch (\PDOException $exception) {
                $statuscode = 500;
            }
        } else {
            $statuscode = 404;
        }

        return new JsonResponse($reactions, $statuscode);
    }



}

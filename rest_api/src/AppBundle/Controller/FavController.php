<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Entity\Fav;
use AppBundle\Entity\Message;

class FavController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"fav"})
     * @Rest\Post("/fav/message/{message_id}/{user_id}")
     */
    public function favPostAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $fav = $em->getRepository('AppBundle:Fav')
        ->findBy(['message' => $request->get('message_id'), 'user' => $request->get('user_id')]);

        if (empty($fav)) {
            $fav = new Fav();
            $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('user_id'));
            $message = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Message')
            ->find($request->get('message_id'));

            if (empty($user)) {
                return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            } else
                $fav->setUser($user);
    
            if (empty($message)) {
                return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
            } else {
                $fav->setMessage($message);
                $em->persist($fav);
                $em->flush();
                $reponse = new JsonResponse();
                $reponse->setStatusCode(JsonResponse::HTTP_CREATED);
                return $reponse;
            }
        } else {
            if ($fav) {
                $em->remove($fav[0]);
                $em->flush();
                $reponse = new JsonResponse();
                $reponse->setStatusCode(JsonResponse::HTTP_NO_CONTENT);
                return $reponse;
            }
        }
    }

    /**
     * @Rest\View(serializerGroups={"fav"})
     * @Rest\Post("/fav/comment/{comment_id}/{user_id}")
     */
    public function favCommentAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $fav = $em->getRepository('AppBundle:Fav')
        ->findBy(['comment' => $request->get('comment_id'), 'user' => $request->get('user_id')]);

        if (empty($fav)) {
            $fav = new Fav();
            $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('user_id'));
            $comment = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Comment')
            ->find($request->get('comment_id'));

            if (empty($user)) {
                return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            } else
                $fav->setUser($user);
    
            if (empty($comment)) {
                return \FOS\RestBundle\View\View::create(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
            } else {
                $fav->setComment($comment);
                $em->persist($fav);
                $em->flush();
                $reponse = new JsonResponse();
                $reponse->setStatusCode(JsonResponse::HTTP_CREATED);
                return $reponse;
            }
        } else {
            if ($fav) {
                $em->remove($fav[0]);
                $em->flush();
                $reponse = new JsonResponse();
                $reponse->setStatusCode(JsonResponse::HTTP_NO_CONTENT);
                return $reponse;
            }
        }
    }
}
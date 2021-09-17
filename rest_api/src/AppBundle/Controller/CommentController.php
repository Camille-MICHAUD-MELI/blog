<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Form\Type\CommentType;
use AppBundle\Entity\Comment;

class CommentController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"comment"})
     * @Rest\Get("/message/{user_id}/users")
     */
    public function getUsercommentAction(Request $request)
    {
        $comment = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Comment')
        ->findBy(['user' => $request->get('user_id')]);
        /* @var $comment Comment */
        
        if (empty($comment)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
        }

        return $comment;   
    }

    /**
     * @Rest\View(serializerGroups={"comment"})
     * @Rest\Get("/message/{message_id}/comment")
     */
    public function getCommentsAction(Request $request)
    {
        $comment = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Comment')
        ->findBy(['message' => $request->get('message_id')]);
        /* @var $comment Comment */
        
        if (empty($comment)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
        }

        return $comment;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"comment"})
     * @Rest\Delete("/comment/{id}")
     */
    public function removeCommentAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $comment = $em->getRepository('AppBundle:Comment')
        ->find($request->get('id'));
        /* @var $comment Comment */

        if ($comment) {
            $em->remove($comment);
            $em->flush();
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"comment"})
     * @Rest\Post("/commentpost")
     */
    public function postMessageAction(Request $request)
    {
        $comment = new Comment();
        $comment->setPublicationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $form = $this->createForm(CommentType::class, $comment);
        $form->submit($request->request->all());
        $user = $this->container->get('security.token_storage')
        ->getToken()
        ->getUser();
        $message = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Message')
        ->find('test');

        if ($form->isValid() && $user != null && $message != null) {
            $comment->setUser($user);
            $comment->setMessage($message);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();
            $response = new JsonResponse($comment);
            $response->setStatusCode(JsonResponse::HTTP_CREATED);
            return $response;
        } else {
            $response = new JsonResponse($comment);
            $response->setStatusCode(JsonResponse::HTTP_CONFLICT);
            return $response;
        }
    }
}
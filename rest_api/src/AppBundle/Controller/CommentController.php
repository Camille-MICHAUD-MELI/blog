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
     * @Rest\View()
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
     * @Rest\View()
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
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
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
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/message/{message_id}/comment/{user_id}")
     */
    public function postCommentAction(Request $request)
    {
        $comment = new Comment();
        $comment->setPublicationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $form = $this->createForm(CommentType::class, $comment);
        $form->submit($request->request->all());
        $user = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:User')
        ->find($request->get('user_id'));
        $message = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Message')
        ->find($request->get('message_id'));

        if (empty($user)) {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        } else
            $comment->setUser($user);

        if (empty($message)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
        } else
            $comment->setMessage($message);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();
            return $comment;
        } else {
            return $form;
        }
    }
}
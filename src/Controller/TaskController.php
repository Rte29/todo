<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function listAction(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $repo = $em->getRepository(Task::class);
        $task = $repo->findBy([], ['createdAt' => 'DESC']);

        $taskAll = $paginator->paginate($task, $request->query->getInt('page', 1), 6);

        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $taskAll
        ]);
    }

    #[Route('/tasks/todo', name: 'task_list_todo')]
    public function listTodo(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $repo = $em->getRepository(Task::class);
        $task = $repo->findBy(['isDone' => false], ['createdAt' => 'DESC']);

        $taskAll = $paginator->paginate($task, $request->query->getInt('page', 1), 6);

        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $taskAll
        ]);
    }

    #[Route('/tasks/isDone', name: 'task_list_isDone')]
    public function listIsDone(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $repo = $em->getRepository(Task::class);
        $task = $repo->findBy(['isDone' => true], ['createdAt' => 'DESC']);

        $taskAll = $paginator->paginate($task, $request->query->getInt('page', 1), 6);

        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $taskAll
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request, EntityManagerInterface $em)
    {

        if ($this->getUser() === null) {
            $this->addFlash('alert', 'Connectez-vous pour ajouter une tâche');
            return $this->redirectToRoute('login');
        }


        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $task->setUser($user);

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($user === $task->getUser() || (($task->getUser()->getUsername() === "anonymous") && ($user->getRoles()[0] === "ROLE_ADMIN"))) {

                $em->flush();
                $this->addFlash('success', 'La tâche a bien été modifiée.');
            } else {
                $this->addFlash('error', 'Vous n\'avez pas les droits de modifier cette tâche.');
            }

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        $task->toggle(!$task->IsisDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if ($user === $task->getUser() || (($task->getUser()->getUsername() === "anonymous") && ($user->getRoles()[0] === "ROLE_ADMIN"))) {

            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {

            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer cette tâche');
        }


        return $this->redirectToRoute('task_list');
    }
}

<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\CreatePostCommand;
use App\Application\Command\DeletePostCommand;
use App\Application\Command\UpdatePostCommand;
use App\Application\Exception\NotFoundException;
use App\Application\Handler\CreatePostHandler;
use App\Application\Handler\DeletePostHandler;
use App\Application\Handler\UpdatePostHandler;
use App\Application\Handler\UserQueryHandler;
use App\Application\Query\PostQuery;
use App\Application\Handler\PostQueryHandler;
use App\Application\Query\UserQuery;
use App\Domain\Model\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PostController
 *
 * Contrôleur pour gérer les opérations CRUD sur les posts.
 */
class PostController extends AbstractController
{
    private CreatePostHandler $createPostHandler;
    private PostQueryHandler $postQueryHandler;
    private DeletePostHandler $deletePostHandler;
    private UpdatePostHandler $updatePostHandler;
    private UserQueryHandler $userQueryHandler;

    /**
     * PostController constructor.
     *
     * @param CreatePostHandler $createPostHandler
     * @param PostQueryHandler $postQueryHandler
     * @param DeletePostHandler $deletePostHandler
     * @param UpdatePostHandler $updatePostHandler
     * @param UserQueryHandler $userQueryHandler
     */
    public function __construct(
        CreatePostHandler $createPostHandler,
        PostQueryHandler $postQueryHandler,
        DeletePostHandler $deletePostHandler,
        UpdatePostHandler $updatePostHandler,
        UserQueryHandler $userQueryHandler
    ) {
        $this->createPostHandler = $createPostHandler;
        $this->postQueryHandler = $postQueryHandler;
        $this->deletePostHandler = $deletePostHandler;
        $this->updatePostHandler = $updatePostHandler;
        $this->userQueryHandler = $userQueryHandler;
    }

    /**
     * Crée un nouveau post.
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function create(Request $request, SerializerInterface $serializer): Response
    {
        $content = $request->getContent();

        try {
            $data = $serializer->decode($content, 'json');

            // Vérifier la présence des champs requis
            if (!isset($data['title']) || !isset($data['content']) || !isset($data['createdAt']) || !isset($data['userName'])) {
                return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
            }

            try {
                $createdAt = new \DateTimeImmutable($data['createdAt']);
            } catch (\Exception $e) {
                return new Response('Invalid date format', Response::HTTP_BAD_REQUEST);
            }

            $userQuery = new UserQuery($data['userName']);
            $user = $this->userQueryHandler->handle($userQuery);

            if (!$user) {
                return new Response('User not found', Response::HTTP_BAD_REQUEST);
            }

            $command = new CreatePostCommand(
                $data['title'],
                $data['content'],
                $createdAt,
                $user
            );

            $this->createPostHandler->handle($command);

            return new Response('Post created', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new Response('Invalid JSON format', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Affiche un post par son ID.
     *
     * @param string $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function show(string $id, SerializerInterface $serializer): JsonResponse
    {
        $query = new PostQuery($id);
        $post = $this->postQueryHandler->handle($query);

        if ($post === null) {
            throw new NotFoundException("Post with ID $id not found");
        }

        $jsonContent = $serializer->serialize($post, 'json', ['groups' => 'post:read']);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * Récupère tous les posts.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getAllPosts(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        $jsonContent = $serializer->serialize($posts, 'json', ['groups' => 'post:read']);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * Met à jour un post par son ID.
     *
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function update(Request $request, string $id): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['content'])) {
            return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $command = new UpdatePostCommand(
            (int) $id,
            $data['title'],
            $data['content']
        );

        $this->updatePostHandler->handle($command);

        return new Response('Post updated', Response::HTTP_OK);
    }

    /**
     * Supprime un post par son ID.
     *
     * @param string $id
     * @return Response
     */
    public function delete(string $id): Response
    {
        $command = new DeletePostCommand((int) $id);
        $this->deletePostHandler->handle($command);

        return new Response('Post deleted', Response::HTTP_OK);
    }
}

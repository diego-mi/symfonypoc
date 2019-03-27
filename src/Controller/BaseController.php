<?php
namespace App\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * BaseController constructor.
     * @param ObjectRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonResponse
     */
    public function findAll(): JsonResponse
    {
        return new JsonResponse($this->repository->findAll());
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);

        $codigoRetorno = is_null($entity) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($entity, $codigoRetorno);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function remove(int $id): Response
    {
        $especialidade = $this->repository->find($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
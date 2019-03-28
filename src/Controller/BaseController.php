<?php
namespace App\Controller;

use App\Helper\EntityFactory\IEntidadeFactory;
use App\Helper\Request\FiltersAndPaginationRequest;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @var IEntidadeFactory
     */
    protected $factory;
    /**
     * @var FiltersAndPaginationRequest
     */
    protected $filtersAndPaginationRequest;

    /**
     * BaseController constructor.
     * @param ObjectRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param IEntidadeFactory $factory
     * @param FiltersAndPaginationRequest $filtersAndPaginationRequest
     */
    protected function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        IEntidadeFactory $factory,
        FiltersAndPaginationRequest $filtersAndPaginationRequest
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->filtersAndPaginationRequest = $filtersAndPaginationRequest;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function findAll(Request $request): JsonResponse
    {
        $orderByParams = $this->filtersAndPaginationRequest->getOrderParams($request);
        $searchByParams = $this->filtersAndPaginationRequest->getFiltersParams($request);
        $paginationParams = $this->filtersAndPaginationRequest->getPaginationParams($request);

        $list = $this->repository->findBy(
            $searchByParams,
            $orderByParams,
            $paginationParams['perPage'],
            $paginationParams['offset']
        );

        return new JsonResponse($list);
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

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entidade = $this->factory->criarEntidade($corpoRequisicao);

        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    public function update(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entidadeRecebida = $this->factory->criarEntidade($corpoRequisicao);

        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $this->updateEntity($entidadeExistente, $entidadeRecebida);
        $this->entityManager->flush();

        return new JsonResponse($entidadeExistente);
    }

    /**
     * @param $entidadeExistente
     * @param $entidadeRecebida
     * @return mixed
     */
    abstract public function updateEntity($entidadeExistente, $entidadeRecebida): void;
}
<?php
namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends BaseController
{
    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    /**
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     * @param MedicoFactory $medicoFactory
     * @param MedicoRepository $medicoRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicoRepository $medicoRepository
    )
    {
        parent::__construct($medicoRepository, $entityManager);
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();

        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequisicao);

        $medicoExistente = $this->buscaMedico($id);

        if (is_null($medicoExistente)) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }

        $medicoExistente
            ->setCrm($medicoEnviado->getCrm())
            ->setNome($medicoEnviado->getNome());

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    /**
     * @param int $id
     * @return Medico|object|null
     */
    private function buscaMedico(int $id): ?Medico
    {
        $medico = $this->repository->find($id);

        return $medico;
    }

    /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     * @param int $especialidadeId
     * @return Response
     */
    public function buscaPorEspecialidade(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }
}
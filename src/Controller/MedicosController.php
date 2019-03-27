<?php
namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MedicoFactory
     */
    private $medicoFactory;
    /**
     * @var MedicoRepository
     */
    private $medicoRepository;

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
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
        $this->medicoRepository = $medicoRepository;
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
     * @Route("/medicos", methods={"GET"})
     * @return Response
     */
    public function buscarTodos(): Response
    {
        $medicoList = $this->medicoRepository->findAll();

        return new JsonResponse($medicoList);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function buscarUm(int $id): Response
    {
        $medico = $this->buscaMedico($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($medico, $codigoRetorno);
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
     * @Route("/medicos/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function remover(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Medico|object|null
     */
    private function buscaMedico(int $id): ?Medico
    {
        $medico = $this->medicoRepository->find($id);

        return $medico;
    }

    /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     * @param int $especialidadeId
     * @return Response
     */
    public function buscaPorEspecialidade(int $especialidadeId): Response
    {
        $medicos = $this->medicoRepository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }
}
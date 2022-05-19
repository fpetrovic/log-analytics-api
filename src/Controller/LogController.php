<?php

namespace App\Controller;

use App\Repository\LogRepository;
use App\Validator\LogSearchParameterValidator;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function count(Request $request, LogRepository $logRepository, LogSearchParameterValidator $logSearchPayloadValidator): Response
    {
        $criteria = $request->query->all();
        $logSearchPayloadValidator->validate($criteria, $this->getParameter('app.supported_log_searching_parameters'));

        $logCount = $logRepository->count($criteria);

        return $this->json([
            'counter' => $logCount,
        ]);
    }
}

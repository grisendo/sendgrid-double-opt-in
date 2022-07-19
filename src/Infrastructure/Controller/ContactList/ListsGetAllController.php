<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\ContactList;

use App\Application\ContactList\ContactListResponse;
use App\Application\ContactList\ContactListsResponse;
use App\Application\ContactList\GetAll\GetAllContactListsQuery;
use Grisendo\DDD\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ListsGetAllController extends AbstractController
{
    private NormalizerInterface $normalizer;

    private QueryBus $queryBus;

    public function __construct(
        NormalizerInterface $normalizer,
        QueryBus $queryBus
    ) {
        $this->normalizer = $normalizer;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $lists = $this->queryBus->ask(new GetAllContactListsQuery());
        if (!($lists instanceof ContactListsResponse)) {
            return new JsonResponse(
                '',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
                true
            );
        }

        $response = array_map(static function (ContactListResponse $response) {
            return [
                'id' => $response->getId(),
                'name' => $response->getName(),
            ];
        }, $lists->getContactLists());

        try {
            return new JsonResponse(
                $this->normalizer->normalize($response),
                Response::HTTP_OK
            );
        } catch (ExceptionInterface) {
            return new JsonResponse(
                '',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
                true
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\ContactList;

use App\Application\ContactList\ContactListResponse;
use App\Application\ContactList\Get\GetContactListQuery;
use App\Domain\ContactList\ContactListNotFoundException;
use Grisendo\DDD\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ListsGetController extends AbstractController
{
    private ValidatorInterface $validator;

    private NormalizerInterface $normalizer;

    private QueryBus $queryBus;

    public function __construct(
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        QueryBus $queryBus
    ) {
        $this->validator = $validator;
        $this->normalizer = $normalizer;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $errors = $this->validateRequest($id);
        if ($errors->count() > 0) {
            try {
                return new JsonResponse(
                    $this->normalizer->normalize($errors),
                    Response::HTTP_BAD_REQUEST
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

        try {
            $list = $this->queryBus->ask(new GetContactListQuery($id));
        } catch (ContactListNotFoundException) {
            return new JsonResponse(
                '',
                Response::HTTP_NOT_FOUND,
                [],
                true
            );
        }
        if (!($list instanceof ContactListResponse)) {
            return new JsonResponse(
                '',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
                true
            );
        }

        try {
            return new JsonResponse(
                $this->normalizer->normalize([
                    'id' => $list->getId(),
                    'name' => $list->getName(),
                ]),
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

    private function validateRequest(
        string $id
    ): ConstraintViolationListInterface {
        $constraint = [
            new Assert\NotBlank(),
            new Assert\Uuid(),
        ];

        return $this->validator->validate($id, $constraint);
    }
}

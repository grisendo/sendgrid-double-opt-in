<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Contacts;

use App\Application\Contacts\Create\CreateContactCommand;
use Grisendo\DDD\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

final class ContactsPostController extends AbstractController
{
    private NormalizerInterface $normalizer;

    private CommandBus $commandBus;

    public function __construct(
        NormalizerInterface $normalizer,
        CommandBus $commandBus
    ) {
        $this->normalizer = $normalizer;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $errors = $this->validateRequest($request);
        if ($errors->count() > 0) {
            return new JsonResponse(
                $this->normalizer->normalize($errors),
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->commandBus->dispatch(
            new CreateContactCommand(
                (string) $request->request->get('id'),
                (string) $request->request->get('list_id'),
                (string) $request->request->get('email'),
                (string) $request->request->get('first_name'),
                (string) $request->request->get('last_name'),
            )
        );

        return new JsonResponse('', Response::HTTP_ACCEPTED, [], true);
    }

    private function validateRequest(
        Request $request
    ): ConstraintViolationListInterface {
        $input = $request->request->all();

        $constraint = new Assert\Collection([
            'allowExtraFields' => false,
            'fields' => [
                'id' => [
                    new Assert\NotBlank(),
                    new Assert\Uuid(),
                ],
                'list_id' => [
                    new Assert\NotBlank(),
                    new Assert\Uuid(),
                ],
                'email' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['max' => 254]),
                ],
                'first_name' => [
                    new Assert\Length(['max' => 50]),
                ],
                'last_name' => [
                    new Assert\Length(['max' => 50]),
                ],
            ],
        ]);

        return Validation::createValidator()->validate($input, $constraint);
    }
}

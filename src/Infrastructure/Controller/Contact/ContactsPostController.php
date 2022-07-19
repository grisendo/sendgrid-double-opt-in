<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Contact;

use App\Application\Contact\Create\CreateContactCommand;
use App\Domain\Contact\CannotGenerateContactTokenException;
use Grisendo\DDD\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ContactsPostController extends AbstractController
{
    private ValidatorInterface $validator;

    private NormalizerInterface $normalizer;

    private CommandBus $commandBus;

    public function __construct(
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        CommandBus $commandBus
    ) {
        $this->validator = $validator;
        $this->normalizer = $normalizer;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $errors = $this->validateRequest($request);
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
            $this->commandBus->dispatch(
                new CreateContactCommand(
                    (string) $request->request->get('id'),
                    (string) $request->request->get('list_id'),
                    (string) $request->request->get('email'),
                    ((string) $request->request->get('first_name')) ?: null,
                    ((string) $request->request->get('last_name')) ?: null,
                )
            );
        } catch (CannotGenerateContactTokenException) {
            return new JsonResponse(
                '',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
                true
            );
        }

        return new JsonResponse('', Response::HTTP_ACCEPTED, [], true);
    }

    private function validateRequest(
        Request $request
    ): ConstraintViolationListInterface {
        $input = $request->request->all();

        $constraint = new Assert\Collection([
            'allowExtraFields' => false,
            'fields' => [
                'id' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Uuid(),
                ]),
                'list_id' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Uuid(),
                ]),
                'email' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['max' => 254]),
                ]),
                'first_name' => new Assert\Optional([
                    new Assert\Length(['max' => 50]),
                ]),
                'last_name' => new Assert\Optional([
                    new Assert\Length(['max' => 50]),
                ]),
            ],
        ]);

        return $this->validator->validate($input, $constraint);
    }
}

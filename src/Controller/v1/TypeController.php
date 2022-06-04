<?php

namespace App\Controller\v1;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: "/types")]
class TypeController extends AbstractController
{
    public function __construct(private readonly ValidatorInterface $validator,
                                private readonly TypeRepository $typeRepository)
    {
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $type = new Type();
        $type->setName($request->get('name'));

        $errors = $this->validator->validate($type);
        if (count($errors) > 0) {
            return $this->json(['error' => (string)$errors], 400);
        }

        $this->typeRepository->add($type, true);
        return $this->json([], 201);
    }
}

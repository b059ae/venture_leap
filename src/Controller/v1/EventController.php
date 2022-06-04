<?php

namespace App\Controller\v1;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: "/events")]
class EventController extends AbstractController
{
    public function __construct(private readonly EventRepository $events,
                                private readonly TypeRepository $types,
                                private readonly ValidatorInterface $validator)
    {
    }

    #[Route('', methods: ["GET"])]
    public function index(Request $request): JsonResponse
    {
        $type = $request->get('type');
        $offset = $request->get('offset');
        $limit = $request->get('limit');
        $data = $this->events->findByType($type, $offset, $limit);
        $events = [];
        foreach ($data as $event){
            $events[] = [
                'id' => $event->getId(),
                'type' => $event->getType()->getName(),
                'details' => $event->getDetails(),
                'created_at' => $event->getCreatedAt(),
            ];
        }

        return $this->json($events);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $event = new Event();
        $event->setDetails($request->get('details'));
        $event->setType($this->types->findOneBy(['name' => $request->get('type')]));
        $event->setCreatedAt($request->get('created_at'));

        $errors = $this->validator->validate($event);
        if (count($errors) > 0) {
            return $this->json(['error' => (string)$errors], 400);
        }

        $this->events->add($event, true);
        return $this->json([], 201);
    }

}

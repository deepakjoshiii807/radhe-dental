<?php

declare(strict_types=1);

namespace App\Services\Firebase;

final class AdminContentService
{
    public function __construct(private readonly FirestoreRepository $repository)
    {
    }

    public function list(string $collection): array
    {
        return $this->repository->all($collection);
    }

    public function find(string $collection, string $id): ?array
    {
        return $this->repository->find($collection, $id);
    }

    public function save(string $collection, array $data, ?string $id = null): string
    {
        if ($id === null) {
            return $this->repository->create($collection, $data);
        }

        $this->repository->update($collection, $id, $data);

        return $id;
    }

    public function delete(string $collection, string $id): void
    {
        $this->repository->delete($collection, $id);
    }
}

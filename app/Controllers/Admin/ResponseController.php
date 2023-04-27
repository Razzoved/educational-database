<?php declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Entity\Entity;
use CodeIgniter\HTTP\Response;
use Exception;

class ResponseController extends BaseController
{
    protected function doDelete(int $id, callable $find, callable $delete, string $entityName = 'entity'): Response
    {
        $entity = $find($id);

        if (!$entity) {
            return $this->response->setStatusCode(
                Response::HTTP_NOT_FOUND,
                'Could not find ' . $entityName . ' with id: ' . $id . '!'
            );
        }

        try {
            $delete($entity);
        } catch (Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not delete ' . $entityName . ', try again later!'
            );
        }

        return $this->response->setJSON($entity);
    }

    protected function toResponse(Entity $entity, array $errors = [], int $statusCode = Response::HTTP_OK): Response
    {
        // $body = $entity->toArray();
        // if ($errors !== []) {
        //     $body['errors'] = $errors;
        // }
        // return $this->response
        //     ->setStatusCode($statusCode >= 100 ? $statusCode : Response::HTTP_INTERNAL_SERVER_ERROR)
        //     ->setJSON($body);
        return $this->response
            ->setStatusCode(
                $statusCode >= 100
                    ? $statusCode
                    : Response::HTTP_INTERNAL_SERVER_ERROR,
                !empty($errors)
                    ? array_shift($errors)
                    : ''
            )
            ->setJSON($entity);
    }
}

<?php declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\ExtendedController;
use CodeIgniter\Entity\Entity;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Exception;

class ResponseController extends ExtendedController
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
        } catch (ValidationException $e) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                $e->getMessage(),
            );
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

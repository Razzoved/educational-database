<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Config as EntitiesConfig;
use App\Entities\Material as EntitiesMaterial;
use App\Models\ConfigModel;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Config extends BaseController
{
    protected const RULES = [
        'default_image' => 'is_image[value]',
        'home_url'      => 'required|valid_url_strict',
        'about_url'     => 'required|valid_url_strict',
    ];

    protected $config;
    protected $materials;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->config = model(ConfigModel::class);
    }

    public function index() : string
    {
        return $this->view('config', [
            'meta_title'   => 'Administration - Config',
            'defaultImage' => $this->config->find('default_image')->value,
            'homeURL'      => $this->config->find('home_url')->value,
            'aboutURL'     => $this->config->find('about_url')->value,
        ]);
    }

    public function save() : Response
    {
        $type = $this->request->getPost('type');
        $value = $this->request->getFile('value') ?? $this->request->getPost('value');

        if (!$type || !$value || !isset(self::RULES[$type])) {
            echo var_dump($type, $value);
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Invalid post data!'
            );
        }
        if (!$this->validate([ 'value' => self::RULES[$type] ])) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                $this->validator->getError((string) $type)
            );
        }

        if ($type === 'default_image') {
            $value = self::uploadDefaultImage((object) $value);
        }
        $config = new EntitiesConfig([
            'id' => $type,
            'value' => $value,
        ]);

        try {
            $this->config->update($type, $config);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "Saving {$type} of value: <strong>[{$value}]</strong> failed!"
            );
        }

        return $this->response->setJSON($config);
    }

    public function resetImage() : Response
    {
        $defaultImage = new EntitiesConfig([
            'id'    => 'default_image',
            'value' => ASSET_PREFIX . 'default_image.png',
        ]);

        try {
            $this->config->update('default_image', $defaultImage);
            if (file_exists(ROOTPATH . ASSET_PREFIX . 'configured_image')) {
                unlink(ROOTPATH . ASSET_PREFIX . 'configured_image');
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        };

        return $this->response->setJSON($defaultImage);
    }

    private static function uploadDefaultImage(UploadedFile $uploadedFile) : string
    {
        $path = WRITEPATH .'uploads' . UNIX_SEPARATOR . $uploadedFile->store();

        $file = new File($path, true);
        $file = $file->move(ROOTPATH . ASSET_PREFIX, 'configured_file', true);

        return ASSET_PREFIX . $file->getBasename();
    }
}

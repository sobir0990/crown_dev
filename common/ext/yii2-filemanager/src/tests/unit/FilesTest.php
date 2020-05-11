<?php namespace jakharbek\filemanager\tests;

use jakharbek\filemanager\dto\FileUploadDTO;
use jakharbek\filemanager\factories\FileFactory;
use jakharbek\filemanager\interfaces\FileFactoryInterface;
use jakharbek\filemanager\interfaces\FileRepositoryInterface;
use jakharbek\filemanager\interfaces\FileServiceInterface;
use jakharbek\filemanager\repositories\FileRepository;
use jakharbek\filemanager\services\FileService;
use Yii;
use yii\web\UploadedFile;

class FilesTest extends \Codeception\Test\Unit
{
    /**
     * @var \jakharbek\filemanager\tests\UnitTester
     */
    protected $tester;
    public $files;
    
    protected function _before()
    {
        $this->files = [
            new UploadedFile([
                'size' => 100,
                'name' => 'image1.jpg',
                'type' => 'image/jpeg',
                'error' => 0,
                'tempName' => '../_data/image1.jpg'
            ]),
            new UploadedFile([
                'size' => 200,
                'name' => 'image2.jpg',
                'type' => 'image/jpeg',
                'error' => 0,
                'tempName' => '../_data/image2.jpg'
            ])
        ];
    }

    protected function _after()
    {
    }

    // tests
    public function testUpload()
    {
        /**
         * @var $service FileServiceInterface
         */
        $service = new FileService();
        $fileUploadDTO = new FileUploadDTO();
        $fileUploadDTO->files = $this->files;
        exit();


        print_r($service->upload($fileUploadDTO));
    }
}
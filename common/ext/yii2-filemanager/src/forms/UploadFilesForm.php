<?php

namespace jakharbek\filemanager\forms;

use jakharbek\filemanager\dto\FileSaveDTO;
use jakharbek\filemanager\dto\FileUploadDTO;
use jakharbek\filemanager\helpers\FileManagerHelper;
use jakharbek\filemanager\services\FileService;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class UploadFilesForm
 * @package jakharbek\filemanager\forms
 */

/**
 * @OA\Schema()
 */
class UploadFilesForm extends Model
{
    public $extensions = [];
    public $maxFiles = 100;
    /**
     * @OA\Property(
     *   property="files",
     *   type="file",
     *   description="Files"
     * )
     */
    public $files;

    /**
     * @OA\Property(
     *   property="title",
     *   type="string",
     *   description="Title"
     * )
     */
    public $title = "";

    /**
     * @OA\Property(
     *   property="description",
     *   type="string",
     *   description="Description"
     * )
     */
    public $description = "";

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['title', 'description'], 'string'],
            ['files', 'file', 'skipOnEmpty' => false, 'maxFiles' => $this->maxFiles, 'extensions' => $this->extensions],
        ]);
    }

    /**
     * @return bool
     * @throws \jakharbek\filemanager\exceptions\FileException
     */
    public function upload()
    {
        $service = new FileService();

        if (!$this->validate()) {
            return false;
        }

        $dto = new FileUploadDTO();
        $dto->files = $this->files;
        $dto->useFileName = FileManagerHelper::useFileName();
        $fileUploadedDTO = $service->upload($dto);
        $fileSaveDTO = new FileSaveDTO();
        $fileSaveDTO->title = $this->title;
        $fileSaveDTO->description = $this->description;
        $fileSaveDTO->domain = getenv('STATIC_URL');
        return $service->save($fileUploadedDTO, $fileSaveDTO);
    }
}
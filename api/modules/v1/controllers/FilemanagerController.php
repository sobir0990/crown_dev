<?php

namespace api\modules\v1\controllers;

use common\components\ApiController;
use jakharbek\filemanager\api\actions\DeleteAction;
use jakharbek\filemanager\api\actions\IndexAction;
use jakharbek\filemanager\api\actions\UploadAction;
use jakharbek\filemanager\api\actions\ViewAction;
use jakharbek\filemanager\models\Files;
use jakharbek\filemanager\models\FilesSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\UpdateAction;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

/**
 * Class TypeController
 * @package api\modules\v1\controllers
 */
class FilemanagerController extends ApiController
{
    public $modelClass = Files::class;
    public $searchModel = FilesSearch::class;

    /**
     * @api {post} /filemanager/uploads Upload files
     * @apiGroup Filemanager
     * @apiName FileUpload
     * @apiDescription Upload multiple files
     * @apiVersion 1.0.0
     * @apiHeader {String} Content-type='multipart/form-data' Content type for file uploads
     *
     * @apiParam {File[]} files[] array of uploaded files
     *
     * @apiSuccess {Object[]} Files array of successfully uploaded files in Files objects
     */
    /**
     * @api {get} /filemanager Files list
     * @apiGroup Filemanager
     * @apiName FilesList
     * @apiDescription List of all uploaded files
     * @apiVersion 1.0.0
     *
     * @apiSuccess {array} items array of Files object
     * @apiSuccess {array} _links canonical links of request
     * @apiSuccess {array} _meta meta data of request
     */
    /**
     * @api {get} /filemanager/:id Get single file
     * @apiGroup Filemanager
     * @apiName GetSingleFile
     * @apiDescription get single file with given ID
     * @apiVersion 1.0.0
     *
     * @apiParam {Integer} id File ID
     *
     * @apiSuccess {Object} File Single file object
     */
    /**
     * @api {delete} /filemanager/:id Delete file
     * @apiGroup Filemanager
     * @apiName DeleteFile
     * @apiDescription Delete file with given ID
     * @apiVersion 1.0.0
     *
     * @apiParam {Integer} id File ID
     *
     * @apiSuccess {Object} File Deleted file object
     */
    /**
     * @return array
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(),[
            'uploads' => UploadAction::class,
            'index' => IndexAction::class,
            'view' => ViewAction::class,
            'delete' => DeleteAction::class,
            'update' => UpdateAction::class
        ]);
    }

    public function actionView($id)
    {
        /**
         * @var $filemanagerRepository FileManagerInterface
         */
//        $filemanagerRepository = Yii::$container->get(FileManagerInterface::class);
//        return $filemanagerRepository->getFileById($id);
    }

    public function actionDelete($id)
    {
        /**
         * @var $filemanagerRepository FileManagerInterface
         */
//        $filemanagerRepository = Yii::$container->get(FileManagerInterface::class);
//        return $filemanagerRepository->removeFile($id);
    }
}

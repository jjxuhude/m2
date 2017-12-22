<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Controller\Adminhtml\Productlabel;

use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Backend\App\Action;
use Magento\MediaStorage\Model\File\UploaderFactory;
use TemplateMonster\ProductLabels\Api\ProductLabelRepositoryInterfaceFactory;

class Save extends \Magento\Backend\App\Action
{

    const PRODUCT_LABEL_IMAGE_NAME = 'product_image_label';
    const CATEGORY_LABEL_IMAGE_NAME = 'category_image_label';
    const PRODUCT_LABEL_IMAGE_BACKGROUND = 'product_text_background';
    const CATEGORY_LABEL_IMAGE_BACKGROUND = 'category_text_background';
    const PRODUCT_LABEL_BASE_PATH = 'tm/productlabel/images';
    /**
     * @var \TemplateMonster\ProductLabels\Api\ProductLabelRepositoryInterface
     */
    protected $_productLabelRepository;

    protected $_adapterFactory;

    protected $_uploader;

    protected $_filesystem;

    protected $_file;

    protected $_fieldArrImg = [
        self::PRODUCT_LABEL_IMAGE_BACKGROUND,
        self::PRODUCT_LABEL_IMAGE_NAME,
        self::CATEGORY_LABEL_IMAGE_NAME,
        self::CATEGORY_LABEL_IMAGE_BACKGROUND
    ];

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param ProductLabelRepositoryInterfaceFactory $productLabelRepository
     * @param AdapterFactory $adapterFactory
     * @param UploaderFactory $uploader
     * @param Filesystem $filesystem
     * @param File $file
     */
    public function __construct(Action\Context $context,
                                ProductLabelRepositoryInterfaceFactory $productLabelRepository,
                                AdapterFactory $adapterFactory,
                                UploaderFactory $uploader,
                                Filesystem $filesystem,
                                File $file)
    {
        $this->_productLabelRepository = $productLabelRepository;
        $this->_adapterFactory = $adapterFactory;
        $this->_uploader = $uploader;
        $this->_filesystem = $filesystem;
        $this->_file = $file;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_ProductLabels::productlabels_save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $fileName = '';
        $resultRedirect = $this->resultRedirectFactory->create();
        $dataOrigin = $this->getRequest()->getPostValue();
        $data = $dataOrigin;
        /**
         * Try to save files if they exist
         * Escape if not exist or redirect for a error
         */
        try {
            $arrayForSave = [];
            $baseMediaPath = self::PRODUCT_LABEL_BASE_PATH;
            $mediaDirectory = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            $mediaAbsolutePath = $mediaDirectory->getAbsolutePath($baseMediaPath);

            foreach ($this->getImageField() as $field) {
                //If field need to delete not try to save
                $delete = $this->_checkIfFieldExists($dataOrigin, $field, 'delete');
                if (!$delete) {
                    $fileName = $this->_saveImageByField($field, $baseMediaPath, $mediaAbsolutePath);
                    if ($fileName) {
                        $arrayForSave[$field] = $fileName;
                            /**
                             * If field was replaced need delete previous image
                             */
                            $value = $this->_checkIfFieldExists($dataOrigin, $field, 'value');
                        if ($value) {
                            $this->_deleteFile($mediaDirectory->getAbsolutePath(), $value);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['smart_label_id' => $this->getRequest()->getParam('smart_label_id')]);
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $smartLabelRepository = $this->_productLabelRepository->create();
        if ($data) {
            $id = $this->getRequest()->getParam('smart_label_id');
            if ($id) {
                $model = $smartLabelRepository->getById($id);
            } else {
                $model = $smartLabelRepository->getModelInstance();
            }
            /**
             * If some files has saved already merge array <field name> => <file name>
             * to post data
             */
            if (isset($arrayForSave) && $arrayForSave) {
                $data = array_merge($data, $arrayForSave);
            }
            /**
             * Prepare data before save data. Remove file or convert name from array to string
             */
            $data = $this->_prepareFieldImg($data, $dataOrigin, $mediaDirectory->getAbsolutePath());

            $data['conditions'] = $data['rule']['conditions'];
            unset($data['rule']);
            $model->loadPost($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this smart label.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['smart_label_id' => $model->getId(), '_current' => true]);
                }
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/index/index');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                $this->messageManager->addException($e, __('Something went wrong while saving the smart label.'));
            }

            return $resultRedirect->setPath('*/*/edit', ['smart_label_id' => $this->getRequest()->getParam('smart_label_id')]);
        }

        return $resultRedirect->setPath('*/index/index');
    }

    /**
     *
     * Try to save file
     *
     * @param $fieldName
     * @param $baseMediaPath
     * @param $mediaAbsolutePath
     * @return string
     * @throws \Exception
     */
    protected function _saveImageByField($fieldName, $baseMediaPath, $mediaAbsolutePath)
    {
        $fileArr = $_FILES;
        if (is_array($fileArr)
            && array_key_exists($fieldName, $fileArr)
            && $fileArr[$fieldName]['name']
        ) {
            $mediaAdapter = $this->_adapterFactory->create();
            $uploader = $this->_uploader->create(['fileId' => $fieldName]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->addValidateCallback($fieldName, $mediaAdapter, 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save($mediaAbsolutePath);

            if (array_key_exists('file', $result) && $result['file']) {
                return $baseMediaPath.$result['file'];
            } else {
                throw new \Exception(__('Can not load image.'));
            }
        }
    }

    /**
     *
     * Delete or convert file name from array to string.
     *
     * @param $data
     * @param $dataOrigin
     * @return mixed
     */
    protected function _prepareFieldImg($data, $dataOrigin, $baseMedia)
    {
        foreach ($this->getImageField() as $field) {
            //Check field for deletion
            $delete = $this->_checkIfFieldExists($dataOrigin, $field, 'delete');
            if ($delete) {
                $value = $this->_checkIfFieldExists($dataOrigin, $field, 'value');
                if ($value) {
                    $this->_deleteFile($baseMedia, $value);
                }
                $data[$field] = '';
            }
            //Prepare field array to value
            $value = $this->_checkIfFieldExists($data, $field, 'value');
            if ($value) {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    /**
     *
     * Search for valueName on array by key field name
     *
     * @param $data
     * @param $field
     * @param $valueName
     * @return bool
     */
    protected function _checkIfFieldExists($data, $field, $valueName)
    {
        if (is_array($data) && array_key_exists($field, $data)) {
            $imgField = $data[$field];
            if (is_array($imgField) && array_key_exists($valueName, $imgField) && $imgField[$valueName]) {
                return $imgField[$valueName];
            }
        }
        return false;
    }

    /**
     *
     * Try to delete file
     *
     * @param $mediaDir
     * @param $fileName
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function _deleteFile($mediaDir, $fileName)
    {
        $fullFileName = $mediaDir.$fileName;
        try {
            if ($this->_file->isExists($fullFileName)) {
                $this->_file->deleteFile($fullFileName);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @return array
     */
    protected function getImageField()
    {
        return $this->_fieldArrImg;
    }
}

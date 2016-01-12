<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;

use Tuna\BannerSlider\Controller\Adminhtml\Banner;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Tuna\BannerSlider\Controller\Adminhtml\Banner
{

    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();

        // check if data sent
        if ($data = $this->getRequest()->getPostValue()) {

            $id = $this->getRequest()->getParam('banner_id');
            $banner = $this->_bannerFactory->create();
            $storeViewId = $this->getRequest()->getParam('store');

            if (!$banner->getId() && $id) {
                $this->messageManager->addError(__('This block no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            //START UPLOAD
            if (isset($_FILES['image']) && isset($_FILES['image']['name']) && strlen($_FILES['image']['name'])) {

                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'image']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();

                    $uploader->addValidateCallback('banner_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Tuna\BannerSlider\Model\Banner::BASE_MEDIA_PATH)
                    );
                    $data['image'] = \Tuna\BannerSlider\Model\Banner::BASE_MEDIA_PATH.$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data['image']) && isset($data['image']['value'])) {
                    if (isset($data['image']['delete'])) {
                        $data['image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['image']['value'])) {
                        $data['image'] = $data['image']['value'];
                    } else {
                        $data['image'] = null;
                    }
                }
            }

            // END UPLOAD

            $localeDate = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            $data['start_time'] = $localeDate->date($data['start_time'])->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i');
            $data['end_time'] = $localeDate->date($data['end_time'])->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i');

            //SAVe
            $banner->setData($data)->setStoreViewId($storeViewId);



            // try to save it
            try {

                // save the data
                $banner->save();
                // display success message
                $this->messageManager->addSuccess(__('You saved the banner.'));
                $this->_getSession()->setFormData(false);
                // clear previously saved data from session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'banner_id' => $banner->getId(),
                            '_current' => true,
                            'store' => $storeViewId,
                            'current_slider_id' => $this->getRequest()->getParam('current_slider_id'),
                            'saveandclose' => $this->getRequest()->getParam('saveandclose'),
                        ]
                    );
                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    return $resultRedirect->setPath(
                        '*/*/new',
                        ['_current' => TRUE]
                    );
                }

                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // save data in session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                // redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['banner_id' => $this->getRequest()->getParam('banner_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }

}

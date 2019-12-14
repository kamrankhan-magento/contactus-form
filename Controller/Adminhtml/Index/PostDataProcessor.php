<?php
namespace FME\Contactus\Controller\Adminhtml\Index;

class PostDataProcessor
{
    protected $dateFilter;
    protected $validatorFactory;
    protected $messageManager;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\View\Model\Layout\Update\ValidatorFactory $validatorFactory
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
        $this->validatorFactory = $validatorFactory;
    }
    public function validate($data){
        $errorNo = true;
        //check $data if necessory
        return $errorNo;
    }
    public function validateRequireEntry(array $data){
        $requiredFields = [
            'name' => __('Name'),
            'status' => __('Status')
        ];
        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addError(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }
}

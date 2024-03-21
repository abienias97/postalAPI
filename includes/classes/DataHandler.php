<?php
    
    require_once 'includes/interfaces/DataHandlerInterface.php';

    class DataHandler implements DataHandlerInterface
    {
        private $dataGetter;
        private $encoder;
        private $errorHandler;
        
        public function __construct(DataGetterInterface $dataGetter, EncoderInterface $encoder, ErrorHandlerInterface $errorHandler) {
            $this->dataGetter = $dataGetter;
            $this->encoder = $encoder;
            $this->errorHandler = $errorHandler;
        }
        
        public function getDataToSendByPostalCode($postalCode) {
            $dataArray = $this->dataGetter->getDataByPostalCode($postalCode);
            if ($dataArray === null) {
                $this->errorHandler->throwError(400, 'Could not find any data for code: '.$postalCode);
            }
            $sendArray = Array();
            
            foreach($dataArray as $key=>$value) {
                if ($key == 'id' || $key == 'date_added' || $key == 'code') continue;
                $sendArray[$key] = $value;
            }
            
            return $this->encoder->encode($sendArray);
        }
    }
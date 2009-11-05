<?php
include_once( 'spyc/spyc.php');

class Axial_Configuration_Yaml extends Axial_Configuration
      {
      protected $fileName ;
      public $useCache = true;
      protected  $_cachePath = null;
      protected $_cacheFileName = null;
      static public $_tempData = array();


      function __construct($fileName,$readOnly = false,$cachePath = null)
            {
            $this->fileName = $fileName;
            $this->_readOnly = $readOnly;
            $this->_cachePath = $cachePath;

            if($cachePath == null)
                  {
                  $this->useCache = false;
                  }
            else
                  {
                  $this->useCache = true;
                  $this->_cacheFileName = $this->_cachePath.'__'.basename($this->fileName);
                  }

            parent::__construct(array(),$this->_readOnly);

            if(!file_exists($this->_cacheFileName) || $this->useCache == false)
                  {

                  $this->load();
                  if($this->useCache == true)
                        {
                        $this->writeCache();
                        }
                  }
            else
                  {

                  $this->readCache();
                  }

            }



      public function save()
            {
            $fp = fopen($this->fileName,'w+');
            fputs($fp, Spyc::YAMLDump($this->toArray(),true,0));
            fclose($fp);

            if($this->useCache == true)
                  {
                  $this->writeCache();
                  }
            }

      public function load()
            {

            $this->setData(Spyc::YAMLLoad($this->fileName));
            }


      private function readCache()
            {
            if(!isset(Axial_Configuration_Yaml::$_tempData[$this->_cacheFileName]))
                  {
                  include($this->_cacheFileName)    ;
                  }
            $this->_data = Axial_Configuration_Yaml::$_tempData[$this->_cacheFileName];
            }



      private function writeCache()
            {
            $fp = fopen($this->_cacheFileName,'w+');
            $phpArray = '<?php  Axial_Configuration_Yaml::$_tempData[\''.$this->_cacheFileName.'\'] = '.var_export($this->_data,true).'; ?>' ;
            fputs($fp,$phpArray);
            fclose($fp);
            }



      public function clearCache()
            {
            unset(Axial_Configuration_Yaml::$_tempData[$this->_cacheFileName]);
            if(file_exists('__'.basename($this->fileName)))
                  {
                  unlink('__'.basename($this->fileName));
                  }
            }

      }
?>
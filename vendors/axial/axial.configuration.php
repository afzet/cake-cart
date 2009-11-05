<?php

class Axial_Configuration implements Countable, Iterator
      {
      protected $_readOnly;
      protected $_index;
      protected $_count;
      protected $_data = array();



      public function __construct($array, $readOnly = false)
            {
            $this->_readOnly = (boolean) $readOnly;
            $this->_index = 0;
            $this->_count = 0;
            $this->setData($array);
            }


      public function setData(array $array)
            {
            foreach ($array as $key => $value)
                  {
                  if (is_array($value))
                        {
                        $this->_data[$key] = new Axial_Configuration($value, $this->_readOnly);
                        }
                  else
                        {
                        $this->_data[$key] = $value;
                        }
                  }
            $this->_count = count($this->_data);
            }




      static public function __set_state( array $array )
            {
            return new Axial_Configuration($array['_data']);
            }



      public function get($name, $default = null)
            {
            $result = $default;
            if (array_key_exists($name, $this->_data))
                  {
                  $result = $this->_data[$name];
                  }
            return $result;
            }



      public function __get($name)
            {
            return $this->get($name);
            }


      public function __set($name, $value)
            {
            if (!$this->_readOnly)
                  {
                  if (is_array($value))
                        {
                        $this->_data[$name] = new Axial_Configuration($value, true);
                        }
                  else
                        {
                        $this->_data[$name] = $value;
                        }
                  $this->_count = count($this->_data);
                  }
            else
                  {
                  throw new Exception('Axial_Configuration is read only');
                  }
            }




      public function toArray()
            {
            $array = array();
            foreach ($this->_data as $key => $value)
                  {
                  if (is_object($value))
                        {
                        $array[$key] = $value->toArray();
                        }
                  else
                        {
                        $array[$key] = $value;
                        }
                  }
            return $array;
            }



      protected function __isset($name)
            {
            return isset($this->_data[$name]);
            }



      public function count()
            {
            return $this->_count;
            }



      public function current()
            {
            return current($this->_data);
            }



      public function key()
            {
            return key($this->_data);
            }



      public function next()
            {
            next($this->_data);
            $this->_index++;
            }



      public function rewind()
            {
            reset($this->_data);
            $this->_index = 0;
            }



      public function valid()
            {
            return $this->_index < $this->_count;
            }


      }
?>
<?php
    
namespace CoreShop;

use Pimcore\Model\Object\AbstractObject;
use Pimcore\Model\Object\CoreShopCart;

class Tool {
    
    public static function formatPrice($price, $currency = "EUR")
    {
        try
        {
            $zCurrency = new \Zend_Currency("de_DE");
            return $zCurrency->toCurrency($price, array('currency' => $zCurrency));
        }
        catch(\Exception $ex)
        {
            echo $ex;
        }
        
        return $price;
    }
    
    public static function formatTax($tax)
    {
        return ($tax * 100) . "%";
    }
    
    public static function prepareCart()
    {
        $cartSession = \Pimcore\Tool\Session::get('CoreShop');

        if($cartSession->cartId)
        {
            $cart = CoreShopCart::getById($cartSession->cartId);

            if($cart instanceof CoreShopCart)
                return $cart;
        }

        $cart = CoreShopCart::prepare();
        $cartSession->cartId = $cart->getId();

        return $cart;
    }
    
    /**
     * Retreive the values in an array
     *
     * @return array
     */
    public static function objectToArray(Object\Concrete $object)
    {
        return self::_objectToArray($object);
    }

    /**
     * Retreive the values in json format
     *
     * @return string
     */
    public static function objectToJson(Object\Concrete $object)
    {
        return \Zend_Json::encode(self::_objectToArray($object));
    }

    /**
     * Re-usable helper method
     * @todo move to the library helpers
     *
     * @static
     * @param $object
     * @param null $fieldDefintions
     * @return array
     */
    protected static function _objectToArray($object, $fieldDefintions=null)
    {
        //if the given object is an array then loop through each element
        if(is_array($object))
        {
            $collections = array();
            foreach($object as $o)
            {
                $collections[] = self::_objectToArray($o, $fieldDefintions);
            }
            return $collections;
        }
        if(!is_object($object)) return false;

        //Custom list field definitions
        if(null === $fieldDefintions)
        {
            $fieldDefintions = $object->getClass()->getFieldDefinitions();
        }

        $collection = array();
        foreach($fieldDefintions as $fd)
        {
            $fieldName = $fd->getName();
            $getter    = "get" . ucfirst($fieldName);
            $value     = $object->$getter();

            switch($fd->getFieldtype())
            {
                case 'fieldcollections':
                    if(($value instanceof Object\Fieldcollection) && is_array($value->getItems()))
                    {
                        /** @var $value Object_Fieldcollection */
                        $def = $value->getItemDefinitions();
                        $collection[$fieldName] = self::_objectToArray($value->getItems(), $def['children']->getFieldDefinitions());
                    }
                    break;

                case 'date':
                    /** @var $value Pimcore_Date */
                    $collection[$fieldName] = ($value instanceof \Pimcore\Date) ? $value->getTimestamp() : 0;
                    break;
                default:
                    /** @var $value string */
                    $collection[$fieldName] = $value;
            }
        }

        //Parent class properties
        $collection['id']  = $object->o_id;
        $collection['key'] = $object->o_key;
        return $collection;
    }
    
    /*
     * Class Mapping Tools
     * They are used to map some instances of CoreShop_base to an defined class (type)
     */

    /**
     * @static
     * @param  $sourceClassName
     * @return string
     */
    public static function getModelClassMapping($sourceClassName, $interfaceToImplement = null) {

        $targetClassName = $sourceClassName;
        
        if(!$interfaceToImplement)
            $interfaceToImplement = $targetClassName;

        if($map = CoreShop\Config::getModelClassMappingConfig()) {
            $tmpClassName = $map->{$sourceClassName};
            
            if($tmpClassName)  {
                if(\Pimcore\Tool::classExists($tmpClassName)) {
                    if(is_subclass_of($tmpClassName, $interfaceToImplement)) {
                        $targetClassName = $tmpClassName;
                    } else {
                        Logger::error("Classmapping for " . $sourceClassName . " failed. '" . $tmpClassName . " is not a subclass of '" . $interfaceToImplement . "'. " . $tmpClassName . " has to extend " . $interfaceToImplement);
                    }
                } else {
                    Logger::error("Classmapping for " . $sourceClassName . " failed. Cannot find class '" . $tmpClassName . "'");
                }
            }
        }

        return $targetClassName;
    }
    
    public static function findOrCreateObjectFolder($path)
    {
        $pathParts = explode("/", $path);
        $currentPath = "/";

        foreach ($pathParts as $part) {
            if (empty($part)) {
                continue;
            }

            $myPath = $currentPath."/".$part;

            $folder = AbstractObject::getByPath($myPath);

            if (!$folder instanceof AbstractObject) {
                $folder = new Object_Folder();
                $folder->setParentId(AbstractObject::getByPath($currentPath)->getId());
                $folder->setKey($part);
                $folder->save();
            }

            $currentPath .= $part."/";
        }

        return $folder;
    }
    
    public static function getWebsiteUrl()
    {
        $pageURL = "http";
         
        if ($_SERVER["HTTPS"] == "on") 
        {
            $pageURL .= "s";
        }
        
        $pageURL .= "://";
        
        if ($_SERVER["SERVER_PORT"] != "80") 
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } 
        else 
        {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
            
        return $pageURL;
    }
}

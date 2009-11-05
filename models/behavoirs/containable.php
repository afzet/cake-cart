<?php
/**
 * SVN FILE: $Id: containable.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Containable Behavoir
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class ContainableBehavior extends ModelBehavior {
    var $runtime = array();

    /**
     * Unbinds an array of $associations recursively from the $model it's being initiated from. Also automatically set's
     * Model::recursive to the required value for fetching all 'contained' associations.
     * 
     * Note: Desipte the function definition you can pass any amount of arguments, either strings or arrays beginning from the 2nd
     * parameter. Just make sure you never set the 2nd one to false as this will mess things up.
     *
     * @param object $model
     * @param array $associations
     * @param boolean $__rootLevel
     * @return boolean
     */
    function contain(&$model, $associations = array(), $__rootLevel = true) {
        static $containments = array(), $rootModel = null;
        
        if (!is_object($model) && empty($model)) {
            return false;
        }
    
        if (!is_bool($__rootLevel)) {
            $__rootLevel = true;
        }
        
        if ($__rootLevel === true) {
            $containments = array();
            $args = func_get_args();
            $associations = call_user_func_array('am', array_slice($args, 1));
            $rootModel = $model->name;
        }
        
        if (!isset($containments[$model->name])) {
            $containments[$model->name] = array();
        }
        
        $depths = array();
        foreach ($associations as $index => $modelName) {
            $childModels = array();
            if (is_array($modelName)) {
                $childModels = $modelName;
            } elseif (is_string($modelName) && is_string($index)) {
                $childModels = (array)$modelName;
            }
            
            if (is_string($index)) {
                $modelName = $index;
            }
            
            if (($dotOffset = strpos($modelName, '.')) !== false) {
                $path = explode('.', $modelName);
                $modelName = array_shift($path);
                $childModels = (array)join('.', $path);
            }
            
            if (!isset($model->{$modelName}) || !is_object($model->{$modelName})) {
                trigger_error(h('ContainableBehavior::contain - Invalid model reference / association: '.$model->name.'->'.$modelName));
                continue;
            }
            
            $containments[$model->name][] = $modelName;
            $this->runtime[$rootModel][$modelName] =& $model->{$modelName};
            if (!empty($childModels)) {
                $depths[] = $this->contain($model->{$modelName}, $childModels, false);
            } else {
                $this->contain($model->{$modelName}, array(), false);
            }
        }
        
        $recursions = 1;
        if (!empty($depths)) {
            $recursions = $recursions + max($depths);
        }
        
        $containments[$model->name] = array_unique($containments[$model->name]);
        
        if (!empty($model->__backAssociation)) {
            $model->__resetAssociations();
        }
        
        $unbind = array();
        foreach (array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany') as $assocType) {
            foreach ($model->{$assocType} as $modelName => $assoc) {
                if (!in_array($modelName, $containments[$model->name])) {
                    $unbind[$assocType][] = $modelName;
                }
            }
        }

        if (!empty($unbind)) {
            $model->unbindModel($unbind);
        }
                
        if ($__rootLevel === true) {
            $model->recursive = $recursions;
            return true;
        }
        
        return $recursions;
    }
    
    /**
     * Resets the association on all models affected by the last find for the primary model
     *
     * @param object $model
     * @param array $results
     * @param boolean $primary
     */
    function afterFind(&$model, $results, $primary) {
        if ($primary !== true) {
            return;
        }
        
        if (!isset($this->runtime[$model->name])) {
            return;
        }
            
        foreach ($this->runtime[$model->name] as $key => &$containedModel) {
            if (!empty($containedModel->__backAssociation)) {
                $containedModel->__resetAssociations();
            }
        }
        unset($this->runtime[$model->name]);
    }
}

?> 
<?php namespace EnjoyWorks\core;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : CustomModel.php
 * @project : Pro_Mamma
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/04/04     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model
{
    public function newQueryWithoutScopes()
    {
        $methods = get_class_methods($this);
        if(in_array('newCustomQuery', $methods)){
            $builder = $this->newEloquentBuilder($this->newBaseQueryBuilder());
            $query = $builder->setModel($this)
                ->with($this->with)
                ->withCount($this->withCount);
            return $this->newCustomQuery($query);
        }else{
            return parent::newQueryWithoutScopes();
        }
    }
}
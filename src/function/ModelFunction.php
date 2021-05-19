<?php namespace EnjoyWorks\core;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : ModelFunc.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/02/05     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ModelFunction
{
    private $exColumns = array("id","num");
    //##################################################################################################################
    //##
    //## >> Method  : Core
    //##
    //##################################################################################################################
    /**
     * Core : Row Modify
     * -----------------------------------------------------------------------------------------------------------------
     * @param $ids
     * @param $data string|array|object (string DB Field Name : Update , "delete": Delete)
     * @param $value
     * @return array|bool
     */
    public function modifyRow($ids, $data, $value = '')
    {
        $res = false;
        if(empty($ids))return $res;

        //### ID Array 변경
        $modelIDs = array();
        if(is_string($ids)){
            array_push($modelIDs, $ids);
        }else{
            $modelIDs = $ids;
        }

        try{
            DB::beginTransaction();
            $res = array();
            foreach ($modelIDs  as $val){
                $model = self::find($val);

                if(is_string($data)){
                    if($data == "delete"){
                        $result = $model->delete();
                    }else{
                        $model->{$data} = $value;
                        $result = $model->save();
                    }
                }else if(is_array($data) || is_object($data)){
                    $data = (array)$data;
                    foreach ($data as $key => $val){
                        $model->{$key} = $val;
                    }
                    $result = $model->save();
                }
                if(!$result){
                    DB::rollback();
                    return false;
                }
                array_push($res, $model);
            }
            DB::commit();
        }catch (QueryException $e){
            DB::rollback();
            return false;
        }

        return $res;
    }

    //##################################################################################################################
    //##
    //## >> Method  :  DataTable
    //##
    //##################################################################################################################

    /**
     * DataTable : DataTable Data
     * -----------------------------------------------------------------------------------------------------------------
     * @param Request $request
     * @param $callback
     * @return array
     */
    public function dataTable(Request $request, $callback)
    {
        $offset = $request->input('start');
        $length = $request->input('length');
        $orders = $request->input('order');
        $columns = $request->input('columns');
        $search = $request->input('search');

        //### CallBack
        $res = array();
        $res['data'] = array();
        $res['draw'] = (int)$request->input('draw');
        $res['offset'] = (int)$request->input('start');
        $model = $callback($res, $search, $columns, $orders);

        //### Rows && Limit
        if($this->table){
            $sqlCalcFoundRows = sprintf('SQL_CALC_FOUND_ROWS `%s`.*', $this->table);
        }else{
            $sqlCalcFoundRows = 'SQL_CALC_FOUND_ROWS *';
        }
        $model = $model->addSelect(["SQL_CALC_FOUND_ROWS" => DB::raw($sqlCalcFoundRows)]);
        $model = $model->limit($length)->offset($offset);
        return $model;
    }

    /**
     * DataTable : DataTable Data Search
     * -----------------------------------------------------------------------------------------------------------------
     * @param $model
     * @param $search
     * @param $callback ($query, $searchLike)
     * @return mixed
     */
    public function dataTableSearch($model, $search, $callback)
    {
        if(empty($search['value']))return $model;
        return $model->where(function($query) use ($search, $callback){
            $searchLike = sprintf("%%%s%%", $search['value']);
            $callback($query, $searchLike);
        });
    }

    /**
     * DataTable : DataTable Data Columns
     * -----------------------------------------------------------------------------------------------------------------
     * @param $model
     * @param $columns
     * @param $callback ($model, $column, $searchValue)
     * @return mixed
     */
    public function dataTableColumns($model, $columns, $callback)
    {
        if(empty($columns))return $model;
        foreach ($columns as $item) {
            if (!empty($item['search']['value']) && $item['search']['value'] !== '000') {
                $searchValue = $item['search']['value'];
                $model = $callback($model, $item['data'], $searchValue);
            }
        }
        return $model;
    }

    /**
     * DataTable : DataTable Data Order By
     * -----------------------------------------------------------------------------------------------------------------
     * @param $model
     * @param $columns
     * @param $orders
     * @param $callback ($model, $column, $direction)
     * @return mixed
     */
    public function dataTableOrderBy($model, $columns, $orders, $callback)
    {
        if(empty($columns) || empty($orders))return $model;
        foreach($orders as $order) {
            $columnsKey = $order['column'];
            $orderData = $columns[$columnsKey];
            if (!empty($orderData) && $orderData['orderable'] == "true" && !in_array($orderData['data'], $this->exColumns)) {
                $model = $callback($model, $orderData['data'], $order['dir']);
            }
        }
        return $model;
    }

    /**
     * DataTable Core : Column Search
     * -----------------------------------------------------------------------------------------------------------------
     * @param $model
     * @param $fieldName
     * @param $searchValue
     * @return mixed
     */
    public function columnSearch($model, $fieldName, $searchValue)
    {
        $searchValueLike = sprintf("%s%%", $searchValue);
        return $model->where($fieldName, 'like', $searchValueLike);
    }

    /**
     * DataTable Core : Column Date Search
     * -----------------------------------------------------------------------------------------------------------------
     * @param $model
     * @param $fieldName
     * @param $searchValue
     * @return mixed
     */
    public function columnDateSearch($model, $fieldName, $searchValue)
    {
        if(empty($searchValue))return $model;
        if(!empty($searchValue) && preg_match("/^\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/", $searchValue)){
            $range = explode(" - ", $searchValue);
            $range[0] = sprintf("%s 00:00:00", $range[0]);
            $range[1] = sprintf("%s 23:59:59", $range[1]);
            return $model->whereBetween($fieldName, $range);
        }else if(!empty($searchValue) && preg_match("/^\d{4}-\d{2}-\d{2}$/", $searchValue)){
            $range = array();
            $range[0] = sprintf("%s 00:00:00", $searchValue);
            $range[1] = sprintf("%s 23:59:59", $searchValue);
            return $model->whereBetween($fieldName, $range);
        }
        return $model;
    }

    //##################################################################################################################
    //##
    //## >> Method  :  Getter && Setter
    //##
    //##################################################################################################################

}
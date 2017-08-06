<?php
/**
 * Created by PhpStorm.
 * User: CEBIT
 * Date: 8/6/2017
 * Time: 11:22 AM
 */

class JOBSEARCH_BOL_RequirementDao extends OW_BaseDao {
    private static $classInstance;
    protected function __construct()
    {
        parent::__construct();
    }
    public static function getInstance(){
        if( self::$classInstance === null){
            self::$classInstance = new self();
        }
    }
    public function getDtoClassName()
    {
        return 'JOBSEARCH_BOL_Company';
    }
    public function getTableName()
    {
        return OW_DF_PREFIX . 'jobsearch_company';

    }
    public function getALLCompanies(){
        return $this->findAll();
    }

}
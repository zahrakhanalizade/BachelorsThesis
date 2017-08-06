<?php
class JOBSEARCH_BOL_CompanyDao extends OW_BaseDao{

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
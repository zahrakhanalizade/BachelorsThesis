<?php

class JOBSEARCH_BOL_ApplyingDao extends OW_BaseDao {
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
        return 'JOBSEARCH_BOL_Applying';
    }
    public function getTableName()
    {
        return OW_DF_PREFIX . 'jobsearch_applying';

    }
    public function getALL(){
        return $this->findAll();
    }

}
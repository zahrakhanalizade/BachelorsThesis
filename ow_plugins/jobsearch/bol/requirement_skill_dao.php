<?php

class JOBSEARCH_BOL_RequirementSkillDao extends OW_BaseDao {
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
        return 'JOBSEARCH_BOL_RequirementSkill';
    }
    public function getTableName()
    {
        return OW_DF_PREFIX . 'jobsearch_requirement_skill';

    }
    public function getALLCompanies(){
        return $this->findAll();
    }

}
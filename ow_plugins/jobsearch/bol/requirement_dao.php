<?php


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
        return self::$classInstance;
    }
    public function getDtoClassName()
    {
        return 'JOBSEARCH_BOL_Requirement';
    }
    public function getTableName()
    {
        return OW_DB_PREFIX . 'jobsearch_requirement';

    }
    public function getALL(){
        return $this->findAll();
    }

    public function add($description , $userId)
    {
        $requirement = new JOBSEARCH_BOL_Requirement();
        $requirement->description = $description;
        $requirement->creator = $userId;
//        $pluginfilesDir = OW::getPluginManager()->getPlugin('jobsearch')->getUserFilesDir();
        JOBSEARCH_BOL_RequirementDao::getInstance()->save($requirement);
        return $requirement;
    }

    public function getCreators(){
        $reqs=$this->getALL();
        $count = count($this->getALL());
        $creators = array();
        for ($i=0; $i<$count; $i++){
            array_push($creators, $reqs[$i]->creator);
        }
        return $creators;
    }

}
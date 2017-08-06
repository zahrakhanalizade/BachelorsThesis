<?php
/**
 * Created by PhpStorm.
 * User: CEBIT
 * Date: 8/6/2017
 * Time: 11:21 AM
 */

class JOBSEARCH_BOL_Requirement extends OW_Entity {

    public $describtion;
    public $skills;

    /**
     * @return mixed
     */
    public function getDescribtion()
    {
        return $this->describtion;
    }

    /**
     * @param mixed $describtion
     */
    public function setDescribtion($describtion)
    {
        $this->describtion = $describtion;
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param mixed $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }


}
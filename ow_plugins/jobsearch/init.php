<?php
/**
 * Created by PhpStorm.
 * User: CEBIT
 * Date: 8/4/2017
 * Time: 2:48 PM
 */


OW::getRouter()->addRoute(new OW_Route('jobsearch.index', 'job', "JOBSEARCH_CTRL_Job", 'index'));
OW::getRouter()->addRoute(new OW_Route('jobsearch.addjob', 'job/addjob', "JOBSEARCH_CTRL_Job", 'addJob'));


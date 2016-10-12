<?php

namespace Consumer\Model\Group;

/**
 * Interface GroupRepositoryInterface
 *
 * @package Consumer\Model\Group
 */
interface GroupRepositoryInterface
{
    /**
     * @return Group[]
     */
    public function getAllGroups();
    
    /**
     * @param $id
     *
     * @return Group
     */
    public function getGroup($id);
}
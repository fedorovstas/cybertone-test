<?php


namespace Consumer\Model\Group;

/**
 * Interface GroupCommandInterface
 *
 * @package Consumer\Model\Group
 */
/**
 * Interface GroupCommandInterface
 *
 * @package Consumer\Model\Group
 */
interface GroupCommandInterface
{
  /**
   * @param Group $group
   *
   * @return mixed
   */
  public function addGroup(Group $group);
  
  /**
   * @param Group $group
   *
   * @return mixed
   */
  public function updateGroup(Group $group);
  
  /**
   * @param Group $group
   *
   * @return mixed
   */
  public function deleteGroup(Group $group);
}
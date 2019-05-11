<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2019/5/11
 * Time: 23:11
 */

namespace James\Sortable;

interface Sortable
{
    /**
     * Modify the field column value.
     */
    public function setSortNumber();

    /**
     * Determine if the order column should be set when saving a new model instance.
     */
    public function shouldSortWhenCreating();


    /**
     * Mobility model
     * @param type | up、down、top、end
     */
    public function move($type = 'up');

}
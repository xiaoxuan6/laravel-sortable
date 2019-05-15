<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2019/5/11
 * Time: 18:22
 */

namespace James\Sortable;

trait SortableTrait
{
    protected static function bootSortableTrait()
    {
        static::creating(function($model){
            if($model->shouldSortWhenCreating())
            {
                $model->setSortNumber();
            }
        });
    }

    public function setSortNumber()
    {
        $filed = $this->getSortColumn();

        $this->$filed = $this->getSortNumber() + 1;
    }

    public function getSortNumber(): int
    {
        return (int) $this->buildSortQuery()->max($this->getSortColumn());
    }

    public function getSortColumn(): string
    {
        return $this->sortable['sort_field'] ?? 'sort';
    }

    public function shouldSortWhenCreating(): bool
    {
        return $this->sortable['sort_when_creating'] ?? true;
    }

    public function buildSortQuery()
    {
        return static::query();
    }

    public function move($type = 'up')
    {
        switch (strtolower(trim($type)))
        {
            case 'up':
                $this->moveUp();
                break;
            case 'down':
                $this->moveDown();
                break;
            case 'top':
                $this->moveToStart();
                break;
            case 'end':
                $this->moveToEnd();
                break;
            default:
                break;
        }

        return $this;
    }

    protected function moveUp()
    {
        $filed = $this->getSortColumn();

        $swapWithModel = $this->buildSortQuery()
            ->where($filed, '<', $this->$filed)
            ->latest($filed)
            ->first();

        if (! $swapWithModel) {
            return $this;
        }

        return $this->swapFieldModel($swapWithModel);
    }

    protected function moveDown()
    {
        $filed = $this->getSortColumn();

        $swapWithModel = $this->buildSortQuery()
            ->where($filed, '>', $this->$filed)
            ->oldest($filed)
            ->first();

        if (! $swapWithModel) {
            return $this;
        }

        return $this->swapFieldModel($swapWithModel);
    }

    protected function moveToStart()
    {
        $filed = $this->getSortColumn();
        $firstModel = $this->buildSortQuery()
            ->oldest($filed)
            ->first();

        if ($firstModel->id === $this->id) {
            return $this;
        }

        return $this->swapFieldModel($firstModel);
    }

    protected function moveToEnd()
    {
        $filed = $this->getSortColumn();
        $endModel = $this->buildSortQuery()
            ->latest($filed)
            ->first();

        if ($endModel->id === $this->id) {
            return $this;
        }

        return $this->swapFieldModel($endModel);
    }

    protected function swapFieldModel($otherModel)
    {
        $filed = $this->getSortColumn();
        $oldOrderOfOtherModel = $otherModel->$filed;

        $otherModel->$filed = $this->$filed;
        $otherModel->save();

        $this->$filed = $oldOrderOfOtherModel;
        $this->save();

        return $this;
    }

}
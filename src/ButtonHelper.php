<?php

namespace Thunderbite\TableHelper;

use URL;

class ButtonHelper
{
    public static function viewLog($model, $id)
    {
        return '<a href="/backstage/' . $model . '/log/' . $id . '" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>';
    }

    public static function restore($model, $id)
    {
        return '<a href="/backstage/' . $model . '/restore/' . $id . '" class="btn btn-default btn-xs" data-method="RESTORE"><i class="fa fa-user-plus"></i></a>';
    }

    public static function delete($model, $id)
    {
        $href = URL::route($model . '.destroy', $id);

        return '<a href="' . $href . '" class="btn btn-default btn-xs" data-method="DELETE"><i class="fas fa-trash-alt"></i></a>';
    }

    public static function edit($model, $id)
    {
        $href = URL::route($model . '.edit', $id);

        return '<a href="' . $href . '" class="btn btn-default btn-xs"><i class="fas fa-pencil-alt"></i></a>';
    }

    // public static function delete($model, $id)
    // {
    //

    //     return '<a href="' . $href . '" class="btn btn-default deleteButton btn-xs" data-method=""><i class=""></i></a>';
    // }

    public static function groupEditDelete($model, $id)
    {
        $return = '<div class="btn-group btn-group-xs">';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default"><i class="fas fa-pencil-alt"></i></a>';
        $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default" data-method="DELETE"><i class="fas fa-trash-alt"></i></a>';
        $return .= '</div>';

        return $return; 
    }

    public static function groupSelectEditDelete($model, $id)
    {
        $return = '<div class="btn-group btn-group-xs">';

        $return .= '<a href="/backstage/game-periods/' . $id . '/use/" class="btn btn-default editButton"><i class="fas fa-play fa-fw"></i></a>';
        
        if (!auth()->user()->readonly) {
            $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default editButton"><i class="fas fa-pencil-alt fa-fw"></i></a>';
            $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="fas fa-trash-alt fa-fw"></i></a>';
        }

        $return .= '</div>';

        return $return;
    }

    // public static function groupSelectLockEditDelete($model, $id)
    // {
    //     // $destoryHref = "/admin/$model/$id";
    //     // $lockHref = "/admin/$model/$id/lock";
    //     $editHref = "/admin/$model/$id/edit";
    //     $selectHref = "/admin/$model/use/$id";
    //     // $cloneHref = "/admin/$model/$id/clone";

    //     $return = '<div class="btn-group btn-group-sm">';

    //     $return .= '<a href="' . $selectHref . '" class="btn btn-default editButton"><i class="fa fa-play"></i></a>';
    //     // $return .= '<a href="'.$lockHref.'" class="btn btn-default editButton"><i class="fa fa-unlock-alt"></i></a>';
    //     $return .= '<a href="' . $editHref . '" class="btn btn-default editButton"><i class="fas fa-pencil-alt"></i></a>';
    //     // $return .= '<a href="'.$cloneHref.'" class="btn btn-default editButton"><i class="fa fa-clone"></i></a>';
    //     // $return .= '<a href="'.$destoryHref.'" class="btn btn-default deleteButton" data-method="DELETE"><i class="fas fa-trash-alt"></i></a>';

    //     $return .= '</div>';

    //     return $return;
    // }
}

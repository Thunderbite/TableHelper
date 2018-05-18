<?php

namespace Thunderbite\TableHelper;

use URL;

class ButtonHelper
{
    private static $bootstrapButtonSize = 'sm';
    private static $fontawesomeClass = 'fas';

    public static function setup()
    {
        // Define the bootstrap and fontawesome stuff
        if (config('tablehelper.bootstrap_version') == 3) {
            self::$fontawesomeClass = 'fa';
            self::$bootstrapButtonSize = 'xs';
        }
    }

    public static function viewLog($model, $id)
    {
        // Setup
        self::setup();

        return '<a href="/admin/' . $model . '/log/' . $id . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '"><i class="' . self::$fontawesomeClass . ' fa-eye"></i></a>';
    }

    public static function delete($model, $id)
    {
        // Setup
        self::setup();

        return '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash-alt"></i></a>';
    }

    public static function edit($model, $id)
    {
        // Setup
        self::setup();

        return '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '"><i class="' . self::$fontawesomeClass . ' fa-pencil-alt"></i></a>';
    }

    public static function groupEditDelete($model, $id)
    {
        // Setup
        self::setup();

        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default"><i class="' . self::$fontawesomeClass . ' fa-pencil-alt"></i></a>';
        $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash-alt"></i></a>';
        $return .= '</div>';

        return $return;
    }

    public static function groupSelectEditDelete($model, $id)
    {
        // Setup
        self::setup();


        $destoryHref = URL::route($model . '.destroy', $id);
        $editHref = URL::route($model . '.edit', $id);
        $selectHref = '/admin/campaigns/use/' . $id;

        if (config('tablehelper.bootstrap_version') == 3) {
            $return = '<div class="btn-group btn-group-sm">';

            $return .= '<a href="' . $selectHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-play"></i></a>';

            if (!auth()->user()->readonly) {
                $return .= '<a href="' . $editHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil"></i></a>';
                $return .= '<a href="' . $destoryHref . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash-o"></i></a>';
            }
            $return .= '</div>';

            return $return;
        }
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';

        $return .= '<a href="' . $selectHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-play fa-fw"></i></a>';

        if (!auth()->user()->readonly) {
            $return .= '<a href="' . $editHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil-alt fa-fw"></i></a>';
            $return .= '<a href="' . $destoryHref . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash-alt fa-fw"></i></a>';
        }

        $return .= '</div>';

        return $return;
    }

    public static function groupSelectLockEditDelete($model, $id)
    {
        // Setup
        self::setup();

        // Setup routes
        $destoryHref = "/admin/$model/$id";
        $lockHref = "/admin/$model/$id/lock";
        $editHref = "/admin/$model/$id/edit";
        $selectHref = "/admin/$model/use/$id";
        $cloneHref = "/admin/$model/$id/clone";

        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . $selectHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-play"></i></a>';
        $return .= '<a href="' . $lockHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-unlock-alt"></i></a>';
        $return .= '<a href="' . $editHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil-alt"></i></a>';
        $return .= '<a href="' . $cloneHref . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-clone"></i></a>';
        $return .= '<a href="' . $destoryHref . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash-alt"></i></a>';
        $return .= '</div>';

        return $return;
    }

    public static function groupViewEditDelete($model, $id)
    {
        // Setup
        self::setup();

        // Setup routes
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="/'.$model.'/'.$id.'" class="btn btn-default editButton">';
        $return .= '<i class="' . self::$fontawesomeClass . ' fa-eye"></i>';
        $return .= '</a>';
        $return .= '<a href="/'.$model.'/'.$id.'/edit" class="btn btn-default editButton">';
        $return .= '<i class="' . self::$fontawesomeClass . ' fa-pencil"></i>';
        $return .= '</a>';
        $return .= '<a href="/'.$model.'/'.$id.'" class="btn btn-default deleteButton" data-method="DELETE">';
        $return .= '<i class="' . self::$fontawesomeClass . ' fa-trash-o"></i>';
        $return .= '</a>';

        $return .= '</div>';

        return $return;
    }

    public static function groupViewEdit($model, $id)
    {   
        // Setup routes
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';

        $return .= '<a href="/'.$model.'/'.$id.'" class="btn btn-default editButton">';
        $return .= '<i class="' . self::$fontawesomeClass . ' fa-eye"></i>';
        $return .= '</a>';

        $return .= '<a href="/'.$model.'/'.$id.'/edit" class="btn btn-default editButton">';
        $return .= '<i class="' . self::$fontawesomeClass . ' fa-pencil"></i>';
        $return .= '</a>';

        $return .= '</div>';

        return $return;
    }
}
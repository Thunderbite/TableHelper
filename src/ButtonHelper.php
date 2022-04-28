<?php

namespace Thunderbite\TableHelper;

use URL;

class ButtonHelper
{
    public static $bootstrapButtonSize = 'sm';
    public static $fontawesomeClass = 'fas';

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

        // Return html
        return '<a href="/' . request()->segment(1) . '/' . $model . '/log/' . $id . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '"><i class="' . self::$fontawesomeClass . ' fa-eye"></i></a>';
    }

    public static function delete($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        return '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash"></i></a>';
    }

    public static function view($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        return '<a href="' . URL::route($model . '.show', $id) . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '"><i class="' . self::$fontawesomeClass . ' fa-eye"></i></a>';
    }

    public static function edit($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        return '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '"><i class="' . self::$fontawesomeClass . ' fa-pencil"></i></a>';
    }

    public static function select($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        return '<a href="' . URL::route($model . '.use', $id) . '" class="btn btn-default btn-' . self::$bootstrapButtonSize . '"><i class="' . self::$fontawesomeClass . ' fa-play"></i></a>';
    }

    public static function groupEditDelete($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default"><i class="' . self::$fontawesomeClass . ' fa-pencil"></i></a>';
        $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash"></i></a>';
        $return .= '</div>';

        return $return;
    }

    public static function groupSelectEditDelete($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.use', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-play fa-fw"></i></a>';

        if (!auth()->user()->readonly) {
            $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil fa-fw"></i></a>';
            $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash fa-fw"></i></a>';
        }

        $return .= '</div>';

        return $return;
    }

    public static function groupSelectLockEditDelete($model, $id)
    {
        // Setup
        self::setup();

        // Setup routes
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.use', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-play"></i></a>';
        $return .= '<a href="' . URL::route($model . '.lock', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-unlock-alt"></i></a>';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil"></i></a>';
        $return .= '<a href="' . URL::route($model . '.clone', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-clone"></i></a>';
        $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash"></i></a>';
        $return .= '</div>';

        return $return;
    }
    
    public static function groupEditCloneDelete($model, $id)
    {
        // Setup
        self::setup();

        // Return html
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default"><i class="' . self::$fontawesomeClass . ' fa-pencil"></i></a>';
        $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash"></i></a>';
        $return .= '<a href="' . URL::route($model . '.clone', $id) . '" class="btn btn-default cloneButton" data-method="CLONE"><i class="' . self::$fontawesomeClass . ' fa-clone fa-fw"></i></a>';
        $return .= '</div>';

        return $return;
    }
    
    public static function groupViewEditDelete($model, $id)
    {
        // Setup
        self::setup();

        // Setup routes
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.show', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-eye fa-fw"></i></a>';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil fa-fw"></i></a>';
        $return .= '<a href="' . URL::route($model . '.destroy', $id) . '" class="btn btn-default deleteButton" data-method="DELETE"><i class="' . self::$fontawesomeClass . ' fa-trash fa-fw"></i></a>';
        $return .= '</div>';

        return $return;
    }

    public static function groupViewEdit($model, $id)
    {
        // Setup
        self::setup();

        // Setup routes
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        $return .= '<a href="' . URL::route($model . '.show', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-eye fa-fw"></i></a>';
        $return .= '<a href="' . URL::route($model . '.edit', $id) . '" class="btn btn-default editButton"><i class="' . self::$fontawesomeClass . ' fa-pencil fa-fw"></i></a>';
        $return .= '</div>';

        return $return;
    }

    public static function groupCustomFields($model, $id, $customFields)
    {
        // Setup
        self::setup();

        // Setup routes
        $return = '<div class="btn-group btn-group-' . self::$bootstrapButtonSize . '">';
        foreach ($customFields as $customField) {
            if (method_exists(self::class, $customField['action']) &&
                $func = call_user_func_array([self::class, $customField['action']], [$model, $id])) {
                $return .= $func;
            } else {
                $class = $customField['class'] ?? '';
                $icon = $customField['icon'] ?? '';
                $actionURL = $customField['actionURL'] ?? URL::route($model . "." . $customField['action'], $id);
                $return .= '<a href="' . $actionURL . '" 
                class="btn btn-default ' . $class . '"><i class="' . self::$fontawesomeClass . ' ' . $icon . '"></i></a>';
            }
        }
        $return .= '</div>';

        return $return;
    }
}

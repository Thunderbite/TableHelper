<?php

namespace Thunderbite\TableHelper;

use Schema;
use Validator;

class TableHelper
{
    protected $columns = [];
    protected $data = [];
    protected $query = null;
    protected $result = null;
    protected $request = null;

    public function __construct($query)
    {
        // Set the Query
        $this->query = $query;
        $columNames = [];

        // Validation
        Validator::make(request()->all(), [
            'start' => 'required|numeric',
            'length' => 'required|numeric',
            'search.value' => 'nullable',
            'order.*.column' => 'nullable|numeric',
            'order.*.dir' => 'nullable|in:desc,asc',
        ])->validate();

        if (!isset($this->query->columns)) {
            if (!isset($query->from) || empty($query->from)) {
                $query->from = $query->getModel()->getTable();
            }
            $columns = Schema::getColumnListing($query->from);
        } else {
            $columns = $this->query->columns;
        }
        // Parse column names
        foreach ($columns as $key => $column) {
            if (strpos($column, ' as ') !== false) {
                $columnName = explode(' ', $column);
                $columNames[$key]['original'] = $columnName[0];
                $columNames[$key]['name'] = end($columnName);
            } else {
                $columNames[$key]['original'] = $column;
                $columNames[$key]['name'] = $column;
            }
        }

        // Set vars
        $this->data['recordsTotal'] = $this->query->count();

        // Where clauses for searching
        if (!is_null(request()->input('search.value'))) {
            $this->query->where(function ($query) use ($columNames) {
                foreach ($columNames as $column) {
                    $query->orWhere($column['original'], 'like', '%' . request()->input('search.value') . '%');
                }
            });
        }

        // Count filtered records
        $this->data['recordsFiltered'] = $this->query->count();

        // Do offset and count
        $this->query = $this->query->offset(request()->input('start'));
        $this->query = $this->query->limit(request()->input('length'));

        // Order
        $orderName = request()->input('columns.' . request()->input('order.0.column') . '.data');
        if ('0' !== $orderName)
            $this->query = $this->query->orderBy($orderName, request()->input('order.0.dir'));

        // Run query
        $this->result = $this->query->get();

        $this->data['data'] = $this->result;
    }

    public static function datatable($query)
    {
        return new self($query);
    }

    public function addColumn($key, $value, $model = null, $customFields = null)
    {
        if (is_string($value)) {
            foreach ($this->data['data'] as $index => $data) {
                if (null !== $customFields) {
                    $this->data['data'][$index]->$key = ButtonHelper::$value($model, $data->id, $customFields);
                } else {
                    $this->data['data'][$index]->$key = ButtonHelper::$value($model, $data->id);
                }
            }
        } elseif (is_callable($value)) {
            foreach ($this->data['data'] as $index => $data) {
                $this->data['data'][$index]->$key = $value($data);
            }
        }

        // Return self
        return $this;
    }

    public function editColumn($key, $function)
    {
        // Loop over all data and change a column if needed
        foreach ($this->data['data'] as $index => $data) {
            if (isset($data->{$key})) {
                $this->data['data'][$index]->$key = $function($data);
            }
        }

        // Return self
        return $this;
    }

    public function generate()
    {
        // Return json
        return $this->data;
    }
}

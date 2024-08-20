<?php

namespace Thunderbite\TableHelper;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Schema;
use Validator;

class TableHelper
{
    protected $columns = [];
    protected $data = [];
    protected $query = null;
    protected $result = null;
    protected $request = null;
    private $button_helper = null;

    public function __construct($query)
    {
        $this->button_helper = config('tablehelper.button_helper') ?? ButtonHelper::class;
        // Set the Query
        $this->query = $query;
        $connection = $this->query->getConnection()->getName();

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
        if ($this->query instanceof \Illuminate\Database\Eloquent\Builder) {
            $q = $query->getQuery();
        }
        $this->data['recordsTotal'] =  $this->data['recordsFiltered'] = DB::connection($connection)->table(DB::raw("({$this->query->toSql()}) as sub"))
            ->mergeBindings($q ?? $this->query)
            ->count();

        // Where clauses for searching
        if (null !== request()->input('search.value')) {
            $columnsInput = request()->input('columns'); //all columns definitions passed from DT
            $query->where(function ($locQuery) use ($columnsInput, $columNames, $query) {
                foreach ($columnsInput as $columnInput) { //check if column can be searched and if actually exists
                    if ($columnInput['searchable'] == 'true' || $columnInput['searchable'] == 'exact') {
                        $eagerLoad = explode('.', $columnInput['name']);
                        if (!empty($eagerLoad[1])) {
                            // check if eager loaded { table.column }
                            try {
                                if ($query->getModel()->{$eagerLoad[0]}()) {
                                    $locQuery->orWhereHas($eagerLoad[0], function ($q) use ($eagerLoad, $columnInput) {
                                        if ($columnInput['searchable'] == 'exact') {
                                            $q->where($eagerLoad[1], request()->input('search.value'));
                                        } else {
                                            $q->where($eagerLoad[1], 'like', request()->input('search.value') . '%');
                                        }
                                    });
                                }
                            } catch (\Exception $e) {
                                continue;
                            }
                        } else {
                            foreach ($columNames as $column) { //if its not, check if column exists
                                if ($eagerLoad[0] === $column['original']) {
                                    if ($columnInput['searchable'] == 'exact') {
                                        $locQuery->orWhere($column['original'], request()->input('search.value'));
                                    } else {
                                        $locQuery->orWhere($column['original'], 'like', request()->input('search.value') . '%');
                                    }

                                }
                            }
                        }
                    }
                }
            });

            // Count filtered records
            if ($this->query instanceof \Illuminate\Database\Eloquent\Builder) {
                $q = $query->getQuery();
            }
            $this->data['recordsFiltered'] = DB::connection($connection)->table(DB::raw("({$this->query->toSql()}) as sub"))
                ->mergeBindings($q ?? $this->query)
                ->count();
        }

        // Do offset and count
        $this->query = $this->query->offset(request()->input('start'));
        $this->query = $this->query->limit(request()->input('length'));

        // Order
        $this->orderColumns();

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
        // Dynamically identify button helper from config
        $button_helper = $this->button_helper;
        if (is_string($value)) {
            foreach ($this->data['data'] as $index => $data) {
                if (null !== $customFields) {
                    $this->data['data'][$index]->$key = $button_helper::$value($model, $data->id, $customFields);
                } else {
                    $this->data['data'][$index]->$key = $button_helper::$value($model, $data->id);
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

    private function orderColumns()
    {
        $orderName = request()->input('columns.' . request()->input('order.0.column') . '.name');
        if ('0' === $orderName || empty($orderName)) {
            $orderName = request()->input('columns.' . request()->input('order.0.column') . '.data');
        }
        if ('0' !== $orderName && !empty($orderName)) {
            $eagerLoad = explode('.', $orderName);
            if (!empty($eagerLoad[1])) { // check if eager loaded { table.column }
                $related = $this->query->getModel()->{$eagerLoad[0]}();
                $model = explode('\\', get_class($related));
                $relationName = $model[count($model) - 1];
                if (in_array($relationName, ['BelongsTo', 'BelongsToMany'])) {
                    $keyOne = $related->getRelated()->getTable() . '.' . $related->getRelated()->getKeyName();
                    $keyTwo = $this->query->getModel()->getTable() . '.' . $related->getRelated()->getForeignKey();
                } else {
                    $keyOne = $related->getQualifiedParentKeyName();
                    $keyTwo = $related->getQualifiedForeignKeyName();
                }
                $this->query->join($related->getRelated()->getTable(), $keyOne, '=', $keyTwo)
                    ->orderBy($related->getRelated()->getTable() . '.' . $eagerLoad[1], request()->input('order.0.dir'))
                        ->orderBy($related->getRelated()->getTable() . '.' .'id',request()->input('order.0.dir'));
            } else
                $this->query = $this->query->orderBy($orderName, request()->input('order.0.dir'))
                    ->orderBy($this->query->from . '.id',request()->input('order.0.dir'));
        }
    }
}

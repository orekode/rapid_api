<?php

namespace App\Services;

use Illuminate\Http\Request;


class GeneralService {

    protected $paramsToOperators = [
        'name' => ['in'],
        'age'  => ['eq']
    ];

    protected $columnMap = [
        'name' => 'name',
        // 'age' => 'age',
    ];

    protected $operatorMap = [
        'in' => 'like',
        'eq' => '=',
    ];

    
    public function valueTransform($operator, $value) {

        if($operator == "like") {
            return "%$value%";
        }

        return $value;
    }


    public function transform( Request $request ) {

        $eloQuery = [];
        
        foreach( $this->paramsToOperators as $param => $operators ) {

            $query = $request->query($param);

            if(!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? null;

            if($column === null) continue;

            foreach( $operators as $operator) {

                if(isset($query[$operator])) {

                    $queryOperator = $this->operatorMap[$operator];

                    $eloQuery[] = [ $column, $queryOperator, $this->valueTransform($queryOperator, $query[$operator]) ];
                }

            }
        }

        return $eloQuery;
    }

}
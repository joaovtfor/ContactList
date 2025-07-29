<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    // Usando mass assignment do Eloquent
    protected $fillable = ['name', 'phone', 'email', 'category', 'state', 'city'];

    //     public static function search($term)
    //     {
    //         $sql = <<< SQL
    //             SELECT *
    //               FROM contacts
    //              WHERE name LIKE %?%
    //                 OR phone LIKE %?%
    //                 OR email LIKE %?%
    //                 OR category LIKE %?%
    //                 OR state LIKE %?%
    //                 OR city LIKE %?%
    // SQL;

    //         $params = array_fill(0, 6, $term);
    //         return DB::select($sql, $params);
    //     }

    public static function getContacts()
    {
        $sql = <<< SQL
                SELECT *
                  FROM contacts
    SQL;

        return DB::select($sql);
    }
}

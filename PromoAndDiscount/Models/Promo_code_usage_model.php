<?php

namespace PromoAndDiscount\Models;

use App\Models\Crud_model;

class Promo_code_usage_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'promo_code_usage';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $promo_code_usage_table = $this->db->prefixTable('promo_code_usage');
        $promo_codes_table = $this->db->prefixTable('promo_codes');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $promo_code_usage_table.id=$id";
        }

        $sql = "SELECT $promo_code_usage_table.*, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS customer_name, $promo_codes_table.code AS promo_code
        FROM $promo_code_usage_table
        LEFT JOIN $users_table ON $users_table.id = $promo_code_usage_table.customer_id
        LEFT JOIN $promo_codes_table ON $promo_codes_table.id = $promo_code_usage_table.promo_code_id
        WHERE $promo_code_usage_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}

<?php

namespace PromoAndDiscount\Models;

use App\Models\Crud_model;

class Promo_codes_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'promo_codes';
        parent::__construct($this->table);
    }

    function is_duplicate_code($code, $id = 0) {
        $result = $this->get_all_where(array("code" => $code, "deleted" => 0));
        if ($result->resultID->num_rows && $result->getRow()->id != $id) {
            return true;
        } else {
            return false;
        }
    }

}

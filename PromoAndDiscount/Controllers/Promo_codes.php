<?php

namespace PromoAndDiscount\Controllers;

use App\Controllers\Security_Controller;

class Promo_codes extends Security_Controller {
    protected $Promo_codes_model;
    protected $Users_model;
    protected $Promo_code_usage_model;
    protected $Invoice_items_model;
    protected $Subscription_items_model;
    protected $Proposal_items_model;
    protected $Estimate_items_model;
    protected $Delivery_note_items_model;
    protected $Purchase_order_items_model;

    public function __construct() {
        parent::__construct();
        $this->Promo_codes_model = new \PromoAndDiscount\Models\Promo_codes_model();
        $this->Users_model = new \App\Models\Users_model();
        $this->Promo_code_usage_model = new \PromoAndDiscount\Models\Promo_code_usage_model();
        $this->Invoice_items_model = new \App\Models\Invoice_items_model();
        $this->Subscription_items_model = new \App\Models\Subscription_items_model();
        $this->Proposal_items_model = new \App\Models\Proposal_items_model();
        $this->Estimate_items_model = new \App\Models\Estimate_items_model();
        $this->Delivery_note_items_model = new \App\Models\Delivery_note_items_model();
        $this->Purchase_order_items_model = new \App\Models\Purchase_order_items_model();
    }

    /**
     * Load the settings view
     */
    public function settings() {
        $client_groups_model = new \App\Models\Client_groups_model();
        $view_data['client_groups'] = $client_groups_model->get_all()->getResult();

        $countries_model = new \App\Models\Countries_model();
        $view_data['countries'] = $countries_model->get_all()->getResult();

        return $this->template->rander("PromoAndDiscount\Views\settings\index", $view_data);
    }

    /**
     * Load the promo code modal form
     */
    public function modal_form() {
        $view_data['model_info'] = $this->Promo_codes_model->get_one($this->request->getPost('id'));
        return $this->template->view('PromoAndDiscount\Views\settings\modal_form', $view_data);
    }

    /**
     * Save a promo code
     */
    public function save() {
        $id = $this->request->getPost('id');
        $data = array(
            "code" => $this->request->getPost('code'),
            "discount_type" => $this->request->getPost('discount_type'),
            "discount_value" => $this->request->getPost('discount_value'),
            "usage_limit" => $this->request->getPost('usage_limit'),
            "usage_per_customer" => $this->request->getPost('usage_per_customer'),
            "min_sale_amount" => $this->request->getPost('min_sale_amount'),
        );

        //ensure promo code is unique
        if ($this->Promo_codes_model->is_duplicate_code($data["code"], $id)) {
            echo json_encode(array("success" => false, 'message' => "This promo code already exists."));
            exit();
        }

        $save_id = $this->Promo_codes_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => "Record saved"));
        } else {
            echo json_encode(array("success" => false, 'message' => "Error occurred"));
        }
    }

    /**
     * Get the promo code list data
     */
    public function list_data() {
        $list_data = $this->Promo_codes_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /**
     * Get a single row of promo code data
     * @param int $id
     * @return array
     */
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Promo_codes_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    /**
     * Make a row for the promo code table
     * @param object $data
     * @return array
     */
    private function _make_row($data) {
        return array(
            $data->code,
            $data->discount_type,
            $data->discount_value,
            $data->usage_limit,
            $data->usage_per_customer,
            $data->min_sale_amount,
            modal_anchor(get_uri("promo_codes/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit promo code", "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => "Delete promo code", "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("promo_codes/delete"), "data-action" => "delete"))
        );
    }

    /**
     * Delete a promo code
     */
    public function delete() {
        $id = $this->request->getPost('id');
        if ($this->Promo_codes_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => "Record deleted"));
        } else {
            echo json_encode(array("success" => false, 'message' => "Record cannot be deleted"));
        }
    }

    /**
     * Apply promo code to an invoice
     */
    public function apply_promo_code_to_invoice($data) {
        return $this->_prepare_promo_code_field('invoice', $data['invoice_id']);
    }

    public function apply_code_to_invoice() {
        $this->_apply_promo_code('invoice');
    }

    /**
     * Apply promo code to a subscription
     */
    public function apply_promo_code_to_subscription($data) {
        return $this->_prepare_promo_code_field('subscription', $data['id']);
    }

    public function apply_code_to_subscription() {
        $this->_apply_promo_code('subscription');
    }

    public function apply_promo_code_to_proposal($data) {
        return $this->_prepare_promo_code_field('proposal', $data['id']);
    }

    public function apply_code_to_proposal() {
        $this->_apply_promo_code('proposal');
    }

    public function apply_promo_code_to_estimate($data) {
        return $this->_prepare_promo_code_field('estimate', $data['id']);
    }

    public function apply_code_to_estimate() {
        $this->_apply_promo_code('estimate');
    }

    public function apply_promo_code_to_delivery_note($data) {
        return $this->_prepare_promo_code_field('delivery_note', $data['id']);
    }

    public function apply_code_to_delivery_note() {
        $this->_apply_promo_code('delivery_note');
    }

    public function apply_promo_code_to_purchase_order($data) {
        return $this->_prepare_promo_code_field('purchase_order', $data['id']);
    }

    public function apply_code_to_purchase_order() {
        $this->_apply_promo_code('purchase_order');
    }

    private function _prepare_promo_code_field($item_type, $item_id) {
        $view_data['item_type'] = $item_type;
        $view_data['item_id'] = $item_id;
        return $this->template->view('PromoAndDiscount\Views\_promo_code_field', $view_data);
    }

    /**
     * Private method to handle promo code application
     * @param string $type
     */
    private function _apply_promo_code($type) {
        $promo_code = $this->request->getPost('promo_code');
        $item_id = $this->request->getPost($type . '_id');

        $model_name = "App\\Models\\" . ucfirst($type) . "s_model";
        $item_model = new $model_name();
        $item_info = $item_model->get_one($item_id);
        $customer_id = $item_info->client_id;

        $promo_code_info = $this->Promo_codes_model->get_one_where(array("code" => $promo_code, "deleted" => 0));

        if (!$promo_code_info) {
            echo json_encode(array("success" => false, 'message' => "Invalid promo code."));
            return;
        }

        //check usage limit
        if ($promo_code_info->usage_limit > 0) {
            $total_usage = $this->Promo_code_usage_model->get_all_where(array("promo_code_id" => $promo_code_info->id, "deleted" => 0))->resultID->num_rows;
            if ($total_usage >= $promo_code_info->usage_limit) {
                echo json_encode(array("success" => false, 'message' => "This promo code has reached its usage limit."));
                return;
            }
        }

        //check usage per customer
        if ($promo_code_info->usage_per_customer > 0) {
            $customer_usage = $this->Promo_code_usage_model->get_all_where(array("promo_code_id" => $promo_code_info->id, "customer_id" => $customer_id, "deleted" => 0))->resultID->num_rows;
            if ($customer_usage >= $promo_code_info->usage_per_customer) {
                echo json_encode(array("success" => false, 'message' => "You have already used this promo code."));
                return;
            }
        }

        //check min sale amount
        if ($promo_code_info->min_sale_amount > 0 && $item_info->total < $promo_code_info->min_sale_amount) {
            echo json_encode(array("success" => false, 'message' => "The minimum sale amount for this promo code is " . $promo_code_info->min_sale_amount));
            return;
        }

        //calculate discount
        $discount_amount = 0;
        if ($promo_code_info->discount_type == "percentage") {
            $discount_amount = $item_info->total * ($promo_code_info->discount_value / 100);
        } else {
            $discount_amount = $promo_code_info->discount_value;
        }

        $item_data = array(
            "title" => "Discount (" . $promo_code_info->code . ")",
            "quantity" => 1,
            "unit_type" => "",
            "rate" => -$discount_amount,
            "total" => -$discount_amount,
        );

        $item_model_name = ucfirst($type) . "_items_model";
        $item_data[$type . "_id"] = $item_id;
        $this->$item_model_name->save($item_data);

        //save usage
        $usage_data = array(
            "promo_code_id" => $promo_code_info->id,
            "customer_id" => $customer_id,
            "sale_item_id" => $item_id,
            "sale_item_type" => $type,
            "usage_date" => get_current_utc_time()
        );
        $this->Promo_code_usage_model->save($usage_data);

        echo json_encode(array("success" => true, 'message' => "Promo code applied successfully."));
    }

    /**
     * Export customers to a CSV file
     */
    public function export_customers() {
        $country = $this->request->getPost('country');
        $client_group = $this->request->getPost('client_group');

        $options = array(
            "country" => $country,
            "client_group_id" => $client_group,
            "user_type" => "client"
        );

        $clients = $this->Users_model->get_details($options)->getResult();

        $filename = "customer_export_" . date("Y-m-d") . ".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');

        $header = array("ID", "First Name", "Last Name", "Email", "Company", "Country", "Client Group");
        fputcsv($file, $header);

        foreach ($clients as $client) {
            $data = array(
                $client->id,
                $client->first_name,
                $client->last_name,
                $client->email,
                $client->company_name,
                $client->country,
                $client->client_group
            );
            fputcsv($file, $data);
        }

        fclose($file);
        exit;
    }

    public function usage_history_list_data() {
        $list_data = $this->Promo_code_usage_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_usage_history_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_usage_history_row($data) {
        $customer = $this->Users_model->get_one($data->customer_id);
        $promo_code = $this->Promo_codes_model->get_one($data->promo_code_id);
        return array(
            $promo_code->code,
            $customer->first_name . " " . $customer->last_name,
            ucfirst($data->sale_item_type) . " #" . $data->sale_item_id,
            $data->usage_date
        );
    }
}

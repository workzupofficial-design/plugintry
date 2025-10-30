<?php echo form_open(get_uri("promo_codes/save"), array("id" => "promo-code-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="code" class=" col-md-3">Code</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "code",
                "name" => "code",
                "value" => $model_info->code,
                "class" => "form-control",
                "placeholder" => "Promo Code",
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="discount_type" class=" col-md-3">Discount Type</label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("discount_type", array(
                "fixed" => "Fixed",
                "percentage" => "Percentage",
            ), $model_info->discount_type, "class='select2'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="discount_value" class=" col-md-3">Discount Value</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "discount_value",
                "name" => "discount_value",
                "value" => $model_info->discount_value,
                "class" => "form-control",
                "placeholder" => "Discount Value",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="usage_limit" class=" col-md-3">Usage Limit</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "usage_limit",
                "name" => "usage_limit",
                "value" => $model_info->usage_limit,
                "class" => "form-control",
                "placeholder" => "Usage Limit",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="usage_per_customer" class=" col-md-3">Usage Per Customer</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "usage_per_customer",
                "name" => "usage_per_customer",
                "value" => $model_info->usage_per_customer,
                "class" => "form-control",
                "placeholder" => "Usage Per Customer",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="min_sale_amount" class=" col-md-3">Min Sale Amount</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "min_sale_amount",
                "name" => "min_sale_amount",
                "value" => $model_info->min_sale_amount,
                "class" => "form-control",
                "placeholder" => "Min Sale Amount",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#promo-code-form").appForm({
            onSuccess: function (result) {
                $("#promo-code-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#name").focus();
    });
</script>

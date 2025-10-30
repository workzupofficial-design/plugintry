<div class="form-group">
    <label for="promo_code" class=" col-md-3">Promo Code</label>
    <div class="col-md-9">
        <div class="input-group">
            <?php
            echo form_input(array(
                "id" => "promo_code",
                "name" => "promo_code",
                "class" => "form-control",
                "placeholder" => "Enter Promo Code",
            ));
            ?>
            <span class="input-group-btn">
                <button id="apply-promo-code-btn" class="btn btn-default" type="button">Apply</button>
            </span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#apply-promo-code-btn').on('click', function() {
            var promo_code = $('#promo_code').val();
            var item_id = <?php echo $item_id; ?>;
            var item_type = '<?php echo $item_type; ?>';
            if (promo_code) {
                $.ajax({
                    url: '<?php echo get_uri("promo_codes/apply_code_to_"); ?>' + item_type,
                    type: 'POST',
                    dataType: 'json',
                    data: {promo_code: promo_code, [item_type + '_id']: item_id},
                    success: function (result) {
                        if (result.success) {
                            appAlert.success(result.message, {duration: 10000});
                            location.reload();
                        } else {
                            appAlert.error(result.message, {duration: 10000});
                        }
                    }
                });
            }
        });
    });
</script>

<div id="page-content" class="page-wrapper clearfix">
    <ul class="nav nav-tabs bg-white title" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#promo-codes-tab" data-bs-target="#promo-codes-tab">Promo Codes</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-export-tab" data-bs-target="#customer-export-tab">Customer Export</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#usage-history-tab" data-bs-target="#usage-history-tab">Usage History</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade show active" id="promo-codes-tab">
            <div class="card">
                <div class="page-title clearfix">
                    <h1>Promo Codes</h1>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("promo_codes/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> Add promo code", array("class" => "btn btn-default", "title" => "Add promo code")); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="promo-code-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="customer-export-tab">
            <div class="card">
                <div class="page-title clearfix">
                    <h1>Customer Export</h1>
                </div>
                <div class="card-body">
                    <?php echo form_open(get_uri("promo_codes/export_customers"), array("id" => "customer-export-form")); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control select2">
                                    <option value="">All Countries</option>
                                    <?php foreach ($countries as $country) { ?>
                                        <option value="<?php echo $country->countryName; ?>"><?php echo $country->countryName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client_group">Client Group</label>
                                <select name="client_group" id="client_group" class="form-control select2">
                                    <option value="">All Groups</option>
                                    <?php foreach ($client_groups as $group) { ?>
                                        <option value="<?php echo $group->id; ?>"><?php echo $group->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4">Export Customers</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="usage-history-tab">
            <div class="card">
                <div class="page-title clearfix">
                    <h1>Usage History</h1>
                </div>
                <div class="table-responsive">
                    <table id="usage-history-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#promo-code-table").appTable({
            source: '<?php echo_uri("promo_codes/list_data") ?>',
            columns: [
                {title: "Code"},
                {title: "Discount Type"},
                {title: "Discount Value"},
                {title: "Usage Limit"},
                {title: "Usage Per Customer"},
                {title: "Min Sale Amount"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ]
        });

        $("#usage-history-table").appTable({
            source: '<?php echo_uri("promo_codes/usage_history_list_data") ?>',
            columns: [
                {title: "Promo Code"},
                {title: "Customer"},
                {title: "Sale Item"},
                {title: "Usage Date"},
            ]
        });

        $(".select2").select2();
    });
</script>

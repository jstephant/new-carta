<script>
    $(document).ready(function() {
		var date = new Date();
        var start_date = date.setDate(date.getDate() - 7);
        $("#start_date").flatpickr({
                dateFormat: "d-m-Y",
                defaultDate: start_date,
                maxDate: new Date(),
            }
        );

        $("#end_date").flatpickr(
            {
                dateFormat: "d-m-Y",
                defaultDate: new Date(),
                maxDate: new Date(),
            }
        );

        $('#start_date, #end_date').change(function(){
            $.ajax({
                url: APP_URL + '/home/lead_status',
                type: 'GET',
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val()
                },
                success: function(result) {
                    $('#lead_status li').remove();
                    var li_lead_status = "";
                    $.each(result, function(index, val){
                        li_lead_status += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
                        li_lead_status += val['status_name'];
                        li_lead_status += `<span class="badge badge-primary badge-pill">`;
                        li_lead_status += val['total'];
                        li_lead_status += `</span>`;
                        li_lead_status += `</li>`;
                    });
                    $("#lead_status").append(li_lead_status);
                },
            });

            $.ajax({
                url: APP_URL + '/home/followup_status',
                type: 'GET',
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val()
                },
                success: function(result) {
                    $('#followup_status li').remove();
                    var li_followup_status = "";
                    $.each(result, function(index, val){
                        li_followup_status += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
                        li_followup_status += val['status_name'];
                        li_followup_status += `<span class="badge badge-primary badge-pill">`;
                        li_followup_status += val['total'];
                        li_followup_status += `</span>`;
                        li_followup_status += `</li>`;
                    });
                    $("#followup_status").append(li_followup_status);
                },
            });
		});

        $("#account_from").flatpickr({
                dateFormat: "d-m-Y",
                defaultDate: start_date,
                maxDate: new Date(),
            }
        );

        $("#account_to").flatpickr(
            {
                dateFormat: "d-m-Y",
                defaultDate: new Date(),
                maxDate: new Date(),
            }
        );

        $('#account_from, #account_to').change(function(){
            $.ajax({
                url: APP_URL + '/home/total_account',
                type: 'GET',
                data: {
                    start_date: $('#account_from').val(),
                    end_date: $('#account_to').val()
                },
                success: function(result) {
                    $('#total_account li').remove();
                    var li_account = `<li class="list-group-item d-flex justify-content-between align-items-center">New Accounts <span class="badge badge-primary badge-pill">` + result + `</span></li>`;
                    $("#total_account").append(li_account);
                },
            });
        });

        $("#sales_from").flatpickr({
                dateFormat: "d-m-Y",
                defaultDate: start_date,
                maxDate: new Date(),
            }
        );

        $("#sales_to").flatpickr(
            {
                dateFormat: "d-m-Y",
                defaultDate: new Date(),
                maxDate: new Date(),
            }
        );

        $('#sales_from, #sales_to').change(function(){
            $.ajax({
                url: APP_URL + '/home/total_sales',
                type: 'GET',
                data: {
                    start_date: $('#sales_from').val(),
                    end_date: $('#sales_to').val()
                },
                success: function(result) {
                    $('#total_sales li').remove();
                    var li_total_sales = "";
                    $.each(result['sales_hpl'], function(index, val){
                        li_total_sales += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
                        li_total_sales += val['sales_material_group3_desc'];
                        li_total_sales += `<span class="badge badge-primary badge-pill">`;
                        li_total_sales += val['total'];
                        li_total_sales += `</span>`;
                        li_total_sales += `</li>`;
                    });
                    $("#total_sales").append(li_total_sales);

                    $('#total_sales2 li').remove();
                    var li_total_sales2 = "";
                    $.each(result['sales_edging'], function(index, val){
                        li_total_sales2 += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
                        li_total_sales2 += val['sales_material_group2_desc'] + ' - ' + val['sales_material_group4_desc'];
                        li_total_sales2 += `<span class="badge badge-primary badge-pill">`;
                        li_total_sales2 += val['total'];
                        li_total_sales2 += `</span>`;
                        li_total_sales2 += `</li>`;
                    });
                    $("#total_sales2").append(li_total_sales2);
                },
            });
        });
    });
</script>
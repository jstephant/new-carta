<script>
    (function() {
		'use strict';
		window.addEventListener('load', function() {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					} else {
                        event.preventDefault()

                        $('#feature_role_table > tbody > tr').each(function() {
                            var id = $(this).find('.menu_carta_id')
                            var a = $(this).find('.view')
                            var a_val = $(this).find('.view_value')
                            var c = $(this).find('.create')
                            var c_val = $(this).find('.create_value')
                            var e = $(this).find('.edit')
                            var e_val = $(this).find('.edit_value')
                            var d = $(this).find('.delete')
                            var d_val = $(this).find('.delete_value')
                            var app = $(this).find('.approval')
                            var app_val = $(this).find('.approval_value')

                            if($(a).prop('checked')===true)
                                $(a_val).prop('value', "1")
                            else $(a_val).prop('value', "0")

                            if($(c).prop('checked')===true)
                                $(c_val).prop('value', '1')
                            else $(c_val).prop('value', '0')

                            if($(e).prop('checked')===true)
                                $(e_val).prop('value', '1')
                            else $(e_val).prop('value', '0')

                            if($(d).prop('checked')===true)
                                $(d_val).prop('value', '1')
                            else $(d_val).prop('value', '0')

                            if($(app).prop('checked')===true)
                                $(app_val).prop('value', '1')
                            else $(app_val).prop('value', '0')
                        });
                        form.submit();
                    }
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
</script>
$(document).ready(function() {
  $('.submitRole').click(function() {    
    var role = $('#Role_role').val();
    if (role == '') {
      $('#assign-role-error').html('Role cannot be blank.');
      $('#assign-role-error').show();
    } else {
      $('.assign-role').submit();
    }
    return false;
  });


  

   /* Disabilitazione utente */
   $('#permission_form_assign').on('click', '#permission_submit_button', function (e) { e.preventDefault(); 
     var sList = "";
     var count_permissions=0;
     var admin_checked=false;
   $('.permission-checkbox').each(function () {
    if(this.checked)
    {
      count_permissions++;
      console.log($(this).val());
      if($(this).val()==3 || $(this).val()=='is_admin') admin_checked=true;
    } 
   });
   if(count_permissions==0) { alert("Attenzione, devi selezionare almeno un ruolo"); return false; }
   else if(count_permissions>1 && admin_checked) { alert("Attenzione, il permesso admin deve essere selezionato da solo. Deve essere univoco."); return false; }
   else { $('#permission_form_assign').submit(); }
  });
});
$(document).ready(function () {  
  $('#assign-roles').click(function(e) {
    e.preventDefault();
    submitUserForm();
  });

  /* Disabilitazione utente */
  $('#rbac_user_table').on('click', '.disable_rbac_users', function (e) { e.preventDefault(); var r = confirm("Attenzione, l'utente verrà disabilitato e il consenso alla privacy (GDPR) verrà impostato su NO. Continuare?"); if (r == true) { $.ajax({ url: $(this).attr('href'), type: "GET", dataType: "json", success: function(data) { if(data && data.response) { if(data.response=='success')  {{ window.location.reload(false); } } }  } }); } else return false; });
});

$('input[type="checkbox"]').on('change', function() {
  $('input[type="checkbox"]').not(this).prop('checked', false);
});

function submitUserForm() {
  var checked = false;  
  var emailRegex = new RegExp('^([0-9a-zA-Z]+[-._+&amp;])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$');
  var isEmailValid = emailRegex.test($.trim($('#User_user_email').val()));
  if ($('#User_user_email').val() == '' || isEmailValid == false) {
    $('#assign-role-error').html('Please provide correct email id');
    $('#assign-role-error').show();
    return false;
  }
  $('.role-checkbox').each(function() {
    if ($(this).prop('checked')) {
      checked = true;
      $('.assign-role').submit();
    }
  });
  if (checked === false) {
    if (checkUserStatus == 'CREATE') {
      $('#assign-role-error').html('Please assign atleast one role.');
      $('#assign-role-error').show();      
    } else {
      var deleteUser = confirm('Do you want to delete all roles from user ?');
      if (deleteUser) {
        $('.assign-role').submit();
      }
    }
  }
  return false;
}
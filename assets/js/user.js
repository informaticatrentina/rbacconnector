$(document).ready(function () {  
  $('#assign-roles').click(function(e) {
    e.preventDefault();
    submitUserForm();
  });
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
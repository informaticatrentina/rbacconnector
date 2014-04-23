$(document).ready(function () {  
  var checkUserStatus = 'CREATE';
  if ($('#User_user_email').val() !== '') {
    checkUserStatus = 'EDIT'
  }
  $('#assign-roles').click(function(e) {
    e.preventDefault();
    submitUserForm(checkUserStatus);
  });
});

function submitUserForm(checkUserStatus) {
  var checked = false;
  if ($('#User_user_email').val() == '') {
    $('#assign-role-error').html('Please provide email id');
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
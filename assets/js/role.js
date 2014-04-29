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
});
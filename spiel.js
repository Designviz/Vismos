var myModal = '';
var myNGModal = '';

$(document).ready(function(){

    myModal = document.getElementById('SignupModal');
    myNGModal = document.getElementById('createGraphModal');

    if(myModal!=null)
    {
      myModal.addEventListener('shown.bs.modal', function () {
        console.log("Hello world!");
      });
    }

    if(myNGModal!=null)
    {
      myNGModal.addEventListener('shown.bs.modal', function () {
        console.log("Hello world!");
      });
    }

    $( "#CreateGraphButton" ).click(function(){
      $('#createGraphModal').modal('show');
    });

    $('#SignupModal').on('show.bs.modal', function () {
      var modal = $(this);
      //modal.find('#formGroupExampleInput').focus();
      //modal.find('.modal-body input').val('New Func');
      console.log("Hello world!");
  });

    $('#createGraphModal').on('show.bs.modal', function () {
        var modal = $(this);
        //modal.find('#formGroupExampleInput').focus();
        //modal.find('.modal-body input').val('New Func');
        console.log("Hello world!");
    });

    $(document).on('click','#BigSignupButton',function(e){
        // this will prevent form and reload page on submit.
        e.preventDefault();
        $.post( "ajax.php", $("#BigSignupForm").serialize(),function( data ) {
            $( "#mainSignupRetVal" ).html( data );
          });
    });

    $(document).on('click','#MobileSignupButton',function(e){
        // this will prevent form and reload page on submit.
        e.preventDefault();
        $.post( "ajax.php", $("#MobileSignupForm").serialize(),function( data ) {
            $( "#mobileSignupRetVal" ).html( data );
          });
    });

    $(document).on('click','#submitloginLarge',function(e){
        // this will prevent form and reload page on submit.
        e.preventDefault();
        $.post( "ajax.php", $("#LargeLoginForm").serialize(),function( data ) {
            $( "#LargeLoginInfoBox" ).html( data );
            eval(document.getElementById("runscript").innerHTML);
          });
    });

    $(document).on('click','#submitloginMobile',function(e){
        // this will prevent form and reload page on submit.
        e.preventDefault();
        $.post( "ajax.php", $("#MobileLoginForm").serialize(),function( data ) {
            $( "#MobileLoginInfoBox" ).html( data );
            eval(document.getElementById("runscript").innerHTML);
          });
    });

    $(document).on('click','#MyProfile',function(e){
        // this will prevent form and reload page on submit.
        e.preventDefault();
        $.get( "ajax.php?a=profile", $("#MobileLoginForm").serialize(),function( data ) {
            $( "#MainContent" ).html( data );           
          });
    });




});

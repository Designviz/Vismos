<div class="modal fade" id="SignupModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-none d-lg-block">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>  
        <h2 class="modal-title">Let's Get Started</h2>
      </div>   
      <div class="modal-header d-lg-none">
        <h6 class="modal-title">Let's Get Started</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>  
      </div>  
      <div class="modal-body">
        <div class="d-none d-lg-block" id="mainSignupRetVal">

        </div>
        <div class="d-lg-none" id="mobileSignupRetVal">

        </div>
      <div class="d-none d-lg-block">
        <form method="post" action="" id="BigSignupForm">
          <div class="form-group">
            <div class="row">
              <div class="col">
                <input name="firstname" type="text" class="form-control" placeholder="First name">
              </div>
              <div class="col">
                <input name="lastname" type="text" class="form-control" placeholder="Last name">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">@</div>
              </div>
              <input name="username" type="text" class="form-control" id="formGroupExampleInput" placeholder="Username">
            </div>
          </div>
          <div class="form-group">
            <input name="email" type="text" class="form-control" id="formGroupExampleInput" placeholder="Email">
          </div>
          <div class="form-group">
            <input name="password" type="password" class="form-control" id="formGroupExampleInput" placeholder="Password">
          </div>
          <div class="form-group">
            <input name="repassword" type="password" class="form-control" id="formGroupExampleInput" placeholder="Retype Password">
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col" style="text-align: left !important;">
                <label for="birthdayHelpBlock">
                  <small id="birthdayHelpBlock" class="form-text text-muted">
                    Birthday <span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Providing your birthday helps us make sure you get the right experience for your age.">?</span>
                  </small>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <select name="month" class="custom-select custom-select-lg mb-3" aria-describedby="birthdayHelpBlock">
                  <option selected value="0">Month</option>
                  <option value="1">Jan</option>
                  <option value="2">Feb</option>
                  <option value="3">Mar</option>
                  <option value="4">Apr</option>
                  <option value="5">May</option>
                  <option value="6">Jun</option>
                  <option value="7">Jul</option>
                  <option value="8">Aug</option>
                  <option value="9">Sep</option>
                  <option value="10">Oct</option>
                  <option value="11">Nov</option>
                  <option value="12">Dec</option>
                </select>
              </div>
              <div class="col">
                <select name="day" class="custom-select custom-select-lg mb-3">
                  <option selected value="0">Day</option>
                  <?php
                    for ($i=0; $i < 32; $i++) { 
                      echo '<option value="'.($i+1).'">'.($i+1).'</option>';
                    }
                  ?>                  
               </select>
              </div>
              <div class="col">
                <select name="year" class="custom-select custom-select-lg mb-3">
                  <option selected value="0">Year</option>
                  <?php
                    $year = date("Y");
                    $nyear = (int)$year;
                    for ($i=0; $i < 116; $i++) { 
                      echo '<option value="'.($nyear-$i).'">'.($nyear-$i).'</option>';
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col" style="text-align: left !important;">
                <label for="birthdayHelpBlock">
                  <small id="birthdayHelpBlock" class="form-text text-muted">
                    Gender <span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="You can change who sees your gender on your profile.">?</span>
                  </small>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="customRadioInline1" name="gender" class="custom-control-input" value="female">
                  <label class="custom-control-label" for="customRadioInline1">Female</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="customRadioInline2" name="gender" class="custom-control-input" value="male">
                  <label class="custom-control-label" for="customRadioInline2">Male</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="customRadioInline3" name="gender" class="custom-control-input" value="other">
                  <label class="custom-control-label" for="customRadioInline3">Other</label>
                </div>
              </div>
            </div>
          </div>
        
        <p><small>By clicking Sign Up, you agree to our Terms, Data Policy and Cookies Policy. You may receive SMS Notifications from us and can opt out any time.</small></p>
        <div class="modal-footer">
        <input type="hidden" name="action" value="signup"/>
        <button type="button" name="submit" id="BigSignupButton" class="btn btn-lg btn-block btn-primary">Sign Up</button>
      </div>
      </div>

      </form>
      </div>
      <div class="d-lg-none m-3">
        <form method="post" action="" id="MobileSignupForm">
          <div class="form-group">
            <div class="row">
              <div class="col">
                <input name="firstname" type="text" class="form-control  form-control-sm" placeholder="First name">
              </div>
              <div class="col">
                <input name="lastname" type="text" class="form-control  form-control-sm" placeholder="Last name">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group input-group-sm mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">@</div>
              </div>
              <input name="username" type="text" class="form-control" id="formGroupExampleInput" placeholder="Username">
            </div>
          </div>
          <div class="form-group">
            <input name="email" type="text" class="form-control form-control-sm" id="formGroupExampleInput" placeholder="Email">
          </div>
          <div class="form-group">
            <input name="password" type="password" class="form-control form-control-sm" id="formGroupExampleInput" placeholder="Password">
          </div>
          <div class="form-group">
            <input name="repassword" type="password" class="form-control form-control-sm" id="formGroupExampleInput" placeholder="Retype Password">
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col" style="text-align: left !important;">
                <label for="birthdayHelpBlock">
                  <small id="birthdayHelpBlock" class="form-text text-muted">
                    Birthday <span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Providing your birthday helps us make sure you get the right experience for your age.">?</span>
                  </small>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <select name="month" class="custom-select custom-select-sm mb-3" aria-describedby="birthdayHelpBlock">
                  <option selected value="0">Month</option>
                  <option value="1">Jan</option>
                  <option value="2">Feb</option>
                  <option value="3">Mar</option>
                  <option value="4">Apr</option>
                  <option value="5">May</option>
                  <option value="6">Jun</option>
                  <option value="7">Jul</option>
                  <option value="8">Aug</option>
                  <option value="9">Sep</option>
                  <option value="10">Oct</option>
                  <option value="11">Nov</option>
                  <option value="12">Dec</option>
                </select>
              </div>
              <div class="col">
                <select name="day" class="custom-select custom-select-sm mb-3">
                  <option selected value="0">Day</option>
                  <?php
                    for ($i=0; $i < 32; $i++) { 
                      echo '<option value="'.($i+1).'">'.($i+1).'</option>';
                    }
                  ?>                  
               </select>
              </div>
              <div class="col">
                <select name="year" class="custom-select custom-select-sm mb-3">
                  <option selected value="0">Year</option>
                  <?php
                    $year = date("Y");
                    $nyear = (int)$year;
                    for ($i=0; $i < 116; $i++) { 
                      echo '<option value="'.($nyear-$i).'">'.($nyear-$i).'</option>';
                    }
                  ?>
                </select>
              </div>
              </div>
              </div>
              <div class="form-group">
              <div class="row">
              <div class="col" style="text-align: left !important;">
                <label for="birthdayHelpBlock">
                  <small id="birthdayHelpBlock" class="form-text text-muted">
                    Gender <span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="You can change who sees your gender on your profile.">?</span>
                  </small>
                </label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="female">
              <label class="form-check-label" for="inlineRadio1">Female</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="male">
              <label class="form-check-label" for="inlineRadio2">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="other">
              <label class="form-check-label" for="inlineRadio3">Other</label>
            </div>            
          </div>  
          <p><small style="font-size: 0.6rem;">By clicking Sign Up, you agree to our Terms, Data Policy and Cookies Policy. You may receive SMS Notifications from us and can opt out any time.</small></p>    
      </div>
        <div class="d-lg-none m-3">
          <div class="modal-footer">
            <input type="hidden" name="action" value="signup"/>
            <button type="button" name="submit" id="MobileSignupButton" class="btn btn-sm btn-block btn-primary">Sign Up</button>
          </div>
        </div>
      </form>
      </div>


    </div>
  </div>
  
</div>
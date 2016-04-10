
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Make Reservation</h2>
  
                </div>
            </div>
          <div id="reservation-content">
            <div class="row text-center">
                <div class="col-lg-12">
                   <!-- reservation form -->

                   <form class="form-horizontal" method="POST" id="reservation_form" onsubmit="return false;">
                   <fieldset>
         


                    <div class="form-group">
                      <label class="col-md-4 control-label" for="client_first_name">First Name</label>  
                      <div class="col-md-8">
                      <input id="client_first_name" name="client_first_name" type="text" placeholder="your first name" class="form-control input-md" required="">
                        
                      </div>
                    </div>
           
                    <div class="form-group">
                     <label class="col-md-4 control-label" for="client_surname">Surname</label>  
                     <div class="col-md-8">
                     <input id="client_surname" name="client_surname" type="text" placeholder="your last name" class="form-control input-md" required="">
                       
                     </div>
                   </div>


                   <div class="form-group">
                     <label class="col-md-4 control-label" for="client_email_address">Email Address</label>  
                     <div class="col-md-8">
                     <input id="client_email_address" name="client_email_address" type="text" placeholder="your email address" class="form-control input-md" required="">
                       
                     </div>
                   </div>

                   <div class="form-group">
                     <label class="col-md-4 control-label" for="check_in_datetime">Check IN</label>
                     <div class="col-md-8">
                       <input id="check_in_datetime" name="check_in_datetime" type="text" placeholder="check in" class="form-control input-md" required="">
                       
                     </div>
                   </div>

    
                   <div class="form-group">
                     <label class="col-md-4 control-label" for="check_out_datetime">Check OUT</label>
                     <div class="col-md-8">
                       <input id="check_out_datetime" name="check_out_datetime" type="text" placeholder="check out" class="form-control input-md" required="">
                       
                     </div>
                   </div>


                   <div class="form-group">
                     <label class="col-md-4 control-label" for="client_telephone">Telephone No</label>  
                     <div class="col-md-8">
                     <input id="client_telephone" name="client_telephone" type="text" placeholder="your telephone number" class="form-control input-md" required="">
                       
                     </div>
                   </div>

                   <div class="form-group">
                     <label class="col-md-4 control-label" for="search_reservation"></label>
                     <div class="col-md-4">
                       <button id="search" name="search" class="btn btn-primary">Search Rooms</button>
                     </div>
                   </div>

                   </fieldset>
                   </form>

                   <!-- end reservation form -->
                 
                </div>
    
            </div>
</div>

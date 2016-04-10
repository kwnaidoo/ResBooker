    </div>
</section>



    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">About</h2>
                    <h3 class="section-subheading text-muted">We strive to make you feel at home</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                    </p>
                </div>
            </div>
        </div>
    </section>



 
    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contact Us</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; Your Website 2014</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#">Privacy Policy</a>
                        </li>
                        <li><a href="#">Terms of Use</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>


    <!-- jQuery -->
    <script src="<?php print BASE_URL;?>themes/default/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php print BASE_URL;?>themes/default/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="<?php print BASE_URL;?>themes/default/js/classie.js"></script>
    <script src="<?php print BASE_URL;?>themes/default/js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="<?php print BASE_URL;?>themes/default/js/jqBootstrapValidation.js"></script>
    <script src="<?php print BASE_URL;?>themes/default/js/contact_me.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php print BASE_URL;?>themes/default/js/agency.js"></script>

    <!-- Datetime Picker -->
    <script src="<?php print BASE_URL;?>themes/default/js/jquery.datetimepicker.full.js"></script>
    <script>
        $(function(){
            $('#check_in_datetime').datetimepicker();  
            $('#check_out_datetime').datetimepicker(); 
            $("#search").click(function(e){
              e.preventDefault();
              $.post( "<?php print BASE_URL;?>Reservations/capture_details", $( "#reservation_form" ).serialize())
                .done(function( response ) {
                  response = JSON.parse(response);
                  if(response.msg.match("success")){
                       window.location = response.url;
                  }else{
                      erros = "Sorry some of the information entered is invalid, please correct the following and try again:\n";
                      for(i in response.errors){
                        erros += response.errors[i] + "\n ";
                      }
                      alert(erros);
                    }
                });

            });
        });

        function toggleRoomSelection(room_id){
            $.post( "<?php print BASE_URL;?>Reservations/toggle_room",{"room_id": room_id})
            .done(function(response){

                response = JSON.parse(response);
                if(response.msg.match("success")){

                    if(response.action_type.match("added")){
                        $("#btn_room_" + room_id).text("DESELECT ROOM");
                     

                    }else{
                        $("#btn_room_" + room_id).text("SELECT ROOM");
           
                    }
                    $("#price_div").html("<b>Total Cost : </b> R" + response.total_cost);
                    $("#rooms_div").html("<b>No. Rooms : </b>" + response.total_rooms);
                    
                }else{
                    alert("Failed to add room to your reservation. Please try again.");
                }
            });
        }

    </script>


</body>

</html>

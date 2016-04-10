
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Make Reservation</h2>
          
                </div>
            </div>
          <div id="reservation-content">
            <div class="row">
                <div class="col-lg-12">
                  <div class="table-responsive">
                  <table class="table table-striped table-hover">
                    <b><i>Please select one or more rooms and click next</i></b><br /><br />
                    <thead class="thead-inverse"><tr><th> Room Name </th> <th>Description</th><th> Price</th><th></th></tr></thead>
                    <tbody>

                      <?php foreach($rooms as $room):?>
                    <tr>
                      <td><?php print $room->name;?></td>
                      <?php if(strlen($room->description) > 20):?>
                          <td><?php print substr($room->description, 0, 20);?>...</td>
                      <?php else:?>
                          <td><?php print $room->description;?></td>
                      <?php endif;?>
                      <td>R<?php print $room->price_normal;?></td>
                      <td><button id="btn_room_<?php print $room->id;?>" class="btn-primary btn-sm" onclick="toggleRoomSelection(<?php print $room->id;?>);">
                        <?php if(in_array($room->id, $rooms_to_book)):?>Deselect Room <?php else:?> Select Room <?php endif;?></button></td>

                    </tr>
                    <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
                <div class="row">
                <div clss="col-lg-7">
                  <div class="col-lg-2" id="price_div">
                    <b>Total Cost : </b> R<?php print $total_cost;?>
                  </div>
                  <div class="col-lg-2" id="rooms_div">
                    <b>No Rooms. : </b> <?php print $total_rooms;?>
                  </div>
                  <div class="col-lg-3" id="rooms_div">
                    <button type="button" class="btn btn-success btn-md" onclick="window.location='<?php print BASE_URL;?>Reservations/confirm#reservation'">Confirm</button>
                    <button type="button" class="btn btn-success btn-md" onclick="window.location='<?php print BASE_URL;?>Reservations/cancel#reservation'">Cancel</button>
                  </div>
                </div>
                <div class="col-lg-5">
                <div class="btn-group" role="group" aria-label="Pagination">
                  <?php $next_url = BASE_URL."Reservations/search_rooms/{$next}#reservation";?>
                  <?php $prev_url = BASE_URL."Reservations/search_rooms/{$prev}#reservation";?>
                  <?php if(1==1):?>
                  <button type="button" class="btn btn-danger btn-md" onclick="window.location='<?php print $prev_url;?>';"><< Prev Rooms</button>
                <?php endif;?>
                <?php if($next != -1):?>
                  <button type="button" class="btn btn-danger btn-md" onclick="window.location='<?php print $next_url;?>';">More Rooms >></button>
                <?php endif;?>
            
                </div>
              </div>
            </div>
                </div>

    
            </div>
         </div>

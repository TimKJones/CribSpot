<?php echo $this->element('header'); ?>
<?php echo $this->Html->css('dashboard'); ?>
<?php echo $this->Html->script('bootstrap'); ?>
<?php echo $this->Html->css('FindSubletPosition'); ?>
<?php echo $this->Html->css('account'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<body>
<div class='container-fluid' id = 'main_content'>
    <div class = 'row-fluid'>
        <div class = 'span3' id = 'left_content'></div>
        <div class='span6' id='middle_content'>
            <div class="sublets form" >
                <div id="sublet-form" title="Create a new sublet">
    
                <?php echo $this->Form->create('Sublet'); ?>
                    <fieldset>
                        <legend><?php echo __('Add Sublet'); ?></legend>
                        <?php
                        //here are the temporary comments
                        //user_id is inserted in model from auth //DONE
                        //university id is selected from javascript list //MODEL
                        //building type is selected from dropdown
                        //building_id is selected from javascript autocomplete form or model
                        //name displays if type is not house
                        //street address is textbox
                        // city is textbox
                        //state is dropdown(use knockout? talk to the guys about this)
                        //ZIP is textbox
                        //latitude and longitude come from the map thing
                        //date_begin and date_end come from date selection boxes(HTML5?)
                        //number of bedrooms is a textbox(dropdown?)
                        //price per bedroom is a textbox
                        //payment type id is triggered from a dropdown
                        //number of bathrooms is a dropdown(or same as num bedrooms)
                        // bathroom type is from prepopulated dropdown
                        // utility type is from prepopulated dropdown
                        // utility cost is textbox
                        //deposit amount is optional textbox
                        // additional fees description is optional textbox
                        //additional fees amount is an optional textfield
                        //talk to guys about images tomorrow
                        //do some crazy jquery for single page registration

                        ?>
                        <script>
                          $(function() {
                            var universitiesMap = 
                              <?php 
                              $universities_json = json_encode($universities);
                              echo $universities_json; ?>;
                            var universitiesArray = [];
                            var currentUniversity;
                            for(var universityIndex=0; universityIndex < universitiesMap.length; universityIndex+=1)
                                universitiesArray.push(universitiesMap[universityIndex].University.name+ ', ' + universitiesMap[universityIndex].University.city + ' ' + universitiesMap[universityIndex].University.state );
                            //for (var university in universitiesMap)
                               // universitiesArray.push(universitiesMap[university]['city']);
                            /*$( "#universities" ).autocomplete({
                              source: universitiesArray
                            });*/
                            console.log(universitiesMap.length);
                            $("#universities").autocomplete({
                                source: function(request, response) {
                                var results = $.ui.autocomplete.filter(universitiesArray, request.term);
                                response(results.slice(0, 10));
                                }
                            });
                          });
                          </script>
                        <?php 
                        //pass the universities array key-value through the sublets view controller
                        //echo $this->Form->select('university_id');
                        
                        
                        //pass the buildtype array key-value through the sublets view controller
                        //also need to make this db table
                        echo $this->Form->input('building_type_id');
                        //use jquery to make autocomplete
                        //echo $this->Form->input('name', array('label'=>'Building Name', 'id'=> 'universities', 'type'=>'text'));
                        ?>
                        <div class="ui-widget">
                          <label for="universities">University: </label>
                          <input id="universities" />
                        </div>
                        <?php
                        echo $this->Form->input('street_address');
                        //echo $this->Form->input('city');
                        //use jquery for the state list
                        //echo $this->Form->input('state');
                        //echo $this->Form->input('zip');
                        //include zip automatically
                        echo $this->Form->input('date_begin', array('class' => 'date-picker','data-html' => 'true', 'data-placement' =>'top', 'type'=>'text')); 
                       
                        echo $this->Form->input('date_end', array('class' => 'date-picker','data-html' => 'true', 'data-placement' =>'top', 'type'=>'text'));
                        echo $this->Form->input('number_bedrooms');
                        echo $this->Form->input('price_per_bedroom');
                        echo $this->Form->input('payment_type_id');
                        echo $this->Form->input('description');
                        echo $this->Form->input('number_bathrooms');
                        echo $this->Form->input('bathroom_type_id');
                        echo $this->Form->input('utility_type_id');
                        echo $this->Form->input('utility_cost');
                        echo $this->Form->input('deposit_amount');
                        echo $this->Form->input('additional_fees_descriptions');
                        echo $this->Form->input('additional_fees_amount');
                    ?>
                    </fieldset>
                <?php echo $this->Form->end('Submit Sublet'); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="hiddenshit" style="display:none;">
    </div>
</div>
</body>


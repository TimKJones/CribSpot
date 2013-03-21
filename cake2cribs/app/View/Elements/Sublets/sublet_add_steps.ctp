<?php
	echo $this->Html->css('/less/login.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Register');
?>

<!-- Modal -->
<div id="sublet_add_steps" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<div id="modal-top">
			<h2 id="modal-logo" class="text-center">CribSpot</h2>
		</div>
		<div id="modal-center">
			<!-- Stuff goes in here -->
			<div id="sublet-form" title="Create a new sublet">
			<form id="sublet_step1">
                    
                        <legend><?php echo __('Add Sublet'); ?></legend>
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
            </form>
                </div>
		</div>
		<div id="modal-bottom">
			<div id="modal-slogan">#SUBLETPROBS</div>
		</div>
	</div>
</div>

<script>

var a = A2Cribs.Register;

a.setupUI();
</script>
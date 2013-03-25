<?php //$this->layout = false; ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>
<?php echo $this->Html->css('FindSubletPosition'); ?>
<style>
        .ui-autocomplete
        {
            position:absolute;
            cursor:default;
            z-index:4000 !important
        }
        #correctLocationMap img {
 		   max-width: none !important;
		}
</style>
<div class="ui-widget">
                          <label for="universitiesInput">University: </label>
                          <input id="universitiesInput" value="<?php echo $savedUniversity; ?>"/>
                        </div>
<div id="sublets form">
<?php echo $this->Form->create('Sublet'); ?>
<fieldset>
<div id="sublet_register_screen1">

	<?php echo $this->Form->input('building_type_id', array('value'=> $savedBuildingTypeID));?>

	<?php echo $this->Form->input('name', array('value'=> $savedName)); ?>
	<?php echo $this->element('correctPinLocationMap'); ?>
	<?php echo $this->Form->input('unit_number', array('value'=> $savedUnitNumber)); ?>

	<!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
	<a href="#" id="goToStep2">Go next </a>
</div>


</div>
 <script>
                          $(function() {
                            var universitiesMap = 
                              <?php 
                              $universities_json = json_encode($universities);
                              echo $universities_json; ?>;
                            var universitiesArray = [];
                            var currentUniversity;
                            window.universitiesMap = universitiesMap;
                            for(var universityIndex=0; universityIndex < universitiesMap.length; universityIndex+=1)
                                universitiesArray.push(universitiesMap[universityIndex].University.name+ ', ' + universitiesMap[universityIndex].University.city + ' ' + universitiesMap[universityIndex].University.state );
                            //for (var university in universitiesMap)
                               // universitiesArray.push(universitiesMap[university]['city']);
                            /*$( "#universities" ).autocomplete({
                              source: universitiesArray
                            });*/
							window.universitiesArray = universitiesArray;
                            $("#universitiesInput").autocomplete({

                                source: function(request, response) {
                                var results = $.ui.autocomplete.filter(universitiesArray, request.term);
                                response(results.slice(0, 10));
                                }
                            });
                          });
						  A2Cribs.CorrectMarker.Init();
						  //A2Cribs.CorrectMarker.Map.setCenter()
						  google.maps.event.trigger(A2Cribs.CorrectMarker.Map, 'resize');

						var a = A2Cribs.SubletAdd;
							a.setupUI();
						$('#addressToMark').val('<?php echo $savedAddress; ?>');
						 
</script>
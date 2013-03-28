<?php //$this->layout = false; ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>
<?php echo $this->Html->css('ajax_sublet'); ?>
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
  <div class = 'sublet-register container-fluid'>
    <div class="ui-widget row-fluid subin">
      <label class = 'span3' for="universitiesInput">University: </label>
      <input class = 'span9' id="universitiesInput"/>
    </div>
    <?php echo $this->Form->create('Sublet'); ?>
    <fieldset>
    <div id="sublet_register_screen1">
      <?php echo $this->Form->input('building_type_id', array(
        'div'=>'row-fluid subin',
        'label'=> array('class'=>'span3','text'=>'Building Type:'),
        'class'=>'span9'
        // 'label'=> 'Building Type:',
        )


      );?>

      <?php echo $this->Form->input('name', array(
        'div'=>'row-fluid subin',
        'label'=> array('class'=>'span3','text'=>'Property Name (optional):'),
        'class'=>'span9'
        )
      ); ?>
      <?php echo $this->element('correctPinLocationMap'); ?>
      <?php echo $this->Form->input('unit_number', array(
        'div'=>'row-fluid subin',
        'label'=> array('class'=>'span3','text'=>'Unit Number:'),
        'class'=>'span9'
        // 'label'=> 'Building Type:',
        )

      );?>

      <!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
    </div>
    <a href="#" id="goToStep2">Go next </a>
  </div>
   <script>
   var universitiesMap = [];
   //A2Cribs.CorrectMarker.SelectedUniversity = null;
   $('#universitiesInput').focusout(function() {
      A2Cribs.CorrectMarker.FindSelectedUniversity();
    });
    $(function() {
      if (A2Cribs.Cache.Step1Data == undefined)
      {
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
        
      }
      else
      {
        $("#universitiesInput").val(A2Cribs.Cache.Step1Data.Sublet.university);
        A2Cribs.CorrectMarker.FindSelectedUniversity();
        $("#SubletBuildingTypeId").val(A2Cribs.Cache.Step1Data.Sublet.building_type_id);
        $("#SubletName").val(A2Cribs.Cache.Step1Data.Sublet.name);
        $("#addressToMark").val(A2Cribs.Cache.Step1Data.Sublet.address);
        latLng = new google.maps.LatLng(A2Cribs.Cache.Step1Data.Sublet.latitude, A2Cribs.Cache.Step1Data.Sublet.longitude)
       // A2Cribs.CorrectMarker.CenterMap(latLng);
        //A2Cribs.CorrectMarker.SetMarkerAtPosition(latLng);
        A2Cribs.CorrectMarker.FindAddress();
        $("#SubletUnitNumber").val(A2Cribs.Cache.Step1Data.Sublet.unit_number);
      }
    });

    $("#universitiesInput").autocomplete({

            source: function(request, response) {
            var results = $.ui.autocomplete.filter(universitiesArray, request.term);
            response(results.slice(0, 10));
            }
        });
        A2Cribs.CorrectMarker.Init();
                
                //A2Cribs.CorrectMarker.Map.setCenter()
                google.maps.event.trigger(A2Cribs.CorrectMarker.Map, 'resize');

            var a = A2Cribs.SubletAdd;
              a.setupUI();
            google.maps.event.trigger(A2Cribs.CorrectMarker.Map, 'resize');
            $('#addressToMark').val('<?php echo $savedAddress; ?>');

            /*<?php if ($savedUniversityId)
            {
             echo 'var currentUniversityFromSave = <?php echo $savedUniversityId; ?>;';
             echo "$('#universitiesInput').val(universitiesArray[currentUniversityFromSave-window.universitiesMap[0].University.id]);";
            } ?>*/
             
</script>

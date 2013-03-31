<?php //$this->layout = false; ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->script('src/SubletEdit') ?>
<?php echo $this->Html->script('src/SubletInProgress') ?>
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
    <div id="search" class="input-append">
      <label for="universityName" class="span3 span3" id="universityNameLabel">University: </label><input id="universityName" class="typeahead" type="text" autocomplete="off">
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
    <a href="#" id="goToStep2" style="float:right">Next</a>
  </div>
   <script>
   var universitiesMap = [];
   //A2Cribs.CorrectMarker.SelectedUniversity = null;
   $('#universityName').focusout(function() {
      A2Cribs.CorrectMarker.FindSelectedUniversity();
    });
    $(function() {
      if (A2Cribs.Cache.Step1Data == undefined)
      {
        var universitiesMap = 
          <?php 
          $universities_json = json_encode($universities);
          echo $universities_json; ?>;
        universities = universitiesMap;
        var universitiesArray = [];
        var currentUniversity;
        window.universitiesMap = universitiesMap;
        A2Cribs.CorrectMarker.universitiesMap = universitiesMap;
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
        $("#universityName").val(A2Cribs.Cache.Step1Data.Sublet.university);
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

        A2Cribs.CorrectMarker.Init();
                
                //A2Cribs.CorrectMarker.Map.setCenter()
                google.maps.event.trigger(A2Cribs.CorrectMarker.Map, 'resize');

            var a = A2Cribs.SubletAdd;
            a.setupUI();
            google.maps.event.trigger(A2Cribs.CorrectMarker.Map, 'resize');
           
schoolList = [];
for (var i = 0; i < universities.length; i++)
    schoolList.push(universities[i].University.name);
    A2Cribs.CorrectMarker.universitiesMap = universities;
    $("#universityName").typeahead({
        source: schoolList
    });

    $("#universityName").focusout(function() {
      A2Cribs.CorrectMarker.FindSelectedUniversity();
    });

    if (A2Cribs.Cache.SubletEditInProgress == null || A2Cribs.Cache.SubletEditInProgress == undefined)
      A2Cribs.SubletEdit.Init();
    A2Cribs.SubletEdit.InitStep1();

</script>

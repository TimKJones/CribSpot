<?php echo $this->Html->css('FindSubletPosition'); ?>
<?php $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/jquery.colorbox/1.4.3/jquery.colorbox-min.js', array('inline' => false)); ?>
<?php echo $this->Html->css('colorbox'); ?>
<body>
<script>
    jQuery(document).ready(function() {
        jQuery('#sublet-form-button').colorbox({inline:true, width:"50%", onComplete:function(){
            A2Cribs.CorrectMarker.Init();
            /*jQuery('#gotoscreen2').click(function(e) {
            e.preventDefault();
            jQuery('#sublet_register_screen1').html(jQuery('#sublet_register_screen2')); */
            jQuery('#gotoscreen2').colorbox({inline:true, width:"50%", onComplete: function() {
                 jQuery('#gotoscreen3').colorbox({inline:true, width:"50%"});
            }});
        }

        });
        jQuery.ajax({
            url: "/sublets/ajax_add",
            context: document.body,
            success: function(response){
                jQuery('#hiddenshit').html(response);
            }
        });

        
    });
</script>
<div class="sublets form">
    <a id="sublet-form-button" href="#sublet_register_screen1">Create a new sublet</a>
    <div id="sublet-form" title="Create a new sublet">
    </div>
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

        <?php 
        //pass the universities array key-value through the sublets view controller
        //echo $this->Form->select('university_id');
        echo $this->Form->input('university_id');
        //pass the buildtype array key-value through the sublets view controller
        //also need to make this db table
        echo $this->Form->input('building_type_id');
        //use jquery to make autocomplete
        echo $this->Form->input('name');
        ?>
        
        <?php
        echo $this->Form->input('street_address');
        echo $this->Form->input('city');
        //use jquery for the state list
        echo $this->Form->input('state');
        echo $this->Form->input('zip');

        echo $this->Form->input('date_begin', array('id' => 'datepicker', 'type'=>'text')); 
       
        echo $this->Form->input('date_end');
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
<div id="hiddenshit" style="display:none;">
</div>
</body>


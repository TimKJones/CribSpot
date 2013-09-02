<?php echo $this->Html->css('posting-sublet-progress'); ?>
<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
    echo $this->Html->script('src/PostSubletProgress');
    echo $this->Html->script('src/SubletSave');
}
?>
<div class = 'post-sublet-progress'>
    
    <div class="prog-step" id = '1'>
        <div>
            <p class="step-label">Basic Info</p>
            <i class="step-state current-step icon-circle-blank"></i>
        </div>
        <div class = 'horz-bar'></div>
    </div>

    <div class="prog-step" id = '2'>
        <div>
            <p class="step-label">Details</p>
            <i class="step-state incomplete-step icon-circle"></i>
        </div>
        <div class = 'horz-bar'></div>
    </div>
    
    <div class="prog-step" id = '3'>
        <div>
            <p class="step-label">Housemates</p>
            <i class="step-state incomplete-step icon-circle"></i>
        </div>
        <div class = 'horz-bar'></div>
    </div>

    <div class="prog-step" id = '4'>
        <div>
            <p class="step-label">Photos</p>
            <i class="step-state incomplete-step icon-circle"></i>
        </div>
    </div>      
</div>

<!-- (span.prog-step>(p.step-label+i.step-state))*4 -->
<?php echo $this->Html->css('posting-sublet-progress'); ?>

<div class = 'post-sublet-progress'>
    
    <div class="prog-step">
        <div>
            <p class="step-label">Address</p>
            <i class="step-state icon-circle"></i>
        </div>
        <div class = 'horz-bar'></div>
    </div>

    <div class="prog-step">
        <div>
            <p class="step-label">Details</p>
            <i class="step-state icon-circle"></i>
        </div>
        <div class = 'horz-bar'></div>
    </div>
    
    <div class="prog-step">
        <div>
            <p class="step-label">House Mates</p>
            <i class="step-state icon-circle"></i>
        </div>
        <div class = 'horz-bar'></div>
    </div>

    <div class="prog-step">
        <div>
            <p class="step-label">Photos</p>
            <i class="step-state icon-circle"></i>
        </div>
    </div>      
</div>

<!-- (span.prog-step>(p.step-label+i.step-state))*4 -->
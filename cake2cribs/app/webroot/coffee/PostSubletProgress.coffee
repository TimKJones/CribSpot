class A2Cribs.PostSubletProgress
    
    constructor: (@Content, @CurrentStep=0)->
        @MaxSteps = $('.prog-step').length - 1
        @updatePositionUI()

    reset: () ->
        @CurrentStep = 0
        @updatePositionUI()

    next:()->
        if @CurrentStep == @MaxSteps
            return
        @CurrentStep++
        @updatePositionUI()

    prev:()->
        if @CurrentState == 0
            return
        @CurrentStep--
        @updatePositionUI()

    updatePositionUI:() ->

        current_step = '<i class="step-state current-step icon-circle-blank"></i>'
        completed_step = '<i class="step-state icon-circle background-icon"></i>
            <i class="step-state complete-step icon-ok-sign"></i>'
        incomplete_step = '<i class="step-state incomplete-step icon-circle"></i>'

        $('.prog-step > div:first-child').each (index, prog_step)=>
            $(prog_step).find('.step-state').remove('.step-state')
            if index < @CurrentStep
                $(prog_step).append(completed_step)
            else if  index == @CurrentStep
                $(prog_step).append(current_step)
            else
                $(prog_step).append(incomplete_step)

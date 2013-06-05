// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.PostSubletProgress = (function() {

    function PostSubletProgress(Content, CurrentStep) {
      this.Content = Content;
      this.CurrentStep = CurrentStep != null ? CurrentStep : 0;
      this.MaxSteps = $('.prog-step').length - 1;
      this.updatePositionUI();
    }

    PostSubletProgress.prototype.reset = function() {
      this.CurrentStep = 0;
      return this.updatePositionUI();
    };

    PostSubletProgress.prototype.next = function() {
      if (this.CurrentStep === this.MaxSteps) {
        return;
      }
      this.CurrentStep++;
      return this.updatePositionUI();
    };

    PostSubletProgress.prototype.prev = function() {
      if (this.CurrentState === 0) {
        return;
      }
      this.CurrentStep--;
      return this.updatePositionUI();
    };

    PostSubletProgress.prototype.updatePositionUI = function() {
      var completed_step, current_step, incomplete_step,
        _this = this;
      current_step = '<i class="step-state current-step icon-circle-blank"></i>';
      completed_step = '<i class="step-state icon-circle background-icon"></i>\
            <i class="step-state complete-step icon-ok-sign"></i>';
      incomplete_step = '<i class="step-state incomplete-step icon-circle"></i>';
      return $('.prog-step > div:first-child').each(function(index, prog_step) {
        $(prog_step).find('.step-state').remove('.step-state');
        if (index < _this.CurrentStep) {
          return $(prog_step).append(completed_step);
        } else if (index === _this.CurrentStep) {
          return $(prog_step).append(current_step);
        } else {
          return $(prog_step).append(incomplete_step);
        }
      });
    };

    return PostSubletProgress;

  })();

}).call(this);
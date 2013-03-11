<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->element('header'); ?>

<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>
<script src='http://knockoutjs.com/examples/resources/knockout.simpleGrid.1.3.js' ></script>
<style>
body { font-family: arial; font-size: 14px; }

.subletList input { font-family: Arial; }
.subletList b { font-weight: bold; }
.subletList p { margin-top: 0.9em; margin-bottom: 0.9em; }
.subletList select[multiple] { width: 100%; height: 8em; }
.subletList h2 { margin-top: 0.4em; }

.ko-grid { margin-bottom: 1em; width: 25em; border: 1px solid silver; background-color:White; }
.ko-grid th { text-align:left; background-color: Black; color:White; }
.ko-grid td, th { padding: 0.4em; }
.ko-grid tr:nth-child(odd) { background-color: #DDD; }
.ko-grid-pageLinks { margin-bottom: 1em; }
.ko-grid-pageLinks a { padding: 0.5em; }
.ko-grid-pageLinks a.selected { background-color: Black; color: White; }
.subletList { height:20em; overflow:auto } /* Mobile Safari reflows pages slowly, so fix the height to avoid the need for reflows */

li { list-style-type: disc; margin-left: 20px; }
</style>

<div>
    <h1> Showing sublets.</h1>
    <table id="sublets">
   <div class='subletList'> 
    <div data-bind='simpleGrid: gridViewModel'> </div>
          <button data-bind='click: sortByName'>
        Sort by name
    </button>
     
    <button data-bind='click: jumpToFirstPage, enable: gridViewModel.currentPageIndex'>
        Jump to first page
    </button>
    
</div>
</table>

</div>

<script type="text/javascript" defer="defer">
jQuery(document).ready(function(){
  
    jQuery.getJSON('/sublets/getSubletsAjax', function (data){
        var test = data[0].Sublet;
        console.log(test);
          var initialData = [ data[0].Sublet, data[1].Sublet, data[2].Sublet];
          console.log(initialData);
    
    var PagedGridModel = function(items) {

    this.items = ko.observableArray(items);
 
 
    this.sortByName = function() {
        this.items.sort(function(a, b) {
            return a.street_address < b.street_address ? -1 : 1;
        });
    };
 
    this.jumpToFirstPage = function() {
        this.gridViewModel.currentPageIndex(0);
    };
 
    this.gridViewModel = new ko.simpleGrid.viewModel({
        data: this.items,
        columns: [
            { headerText: "Address", rowText: "street_address" },
            { headerText: "Number of Bedrooms", rowText: "number_bedrooms" },
            { headerText: "Cost Per Bedroom", rowText: function (item) { return "$" + item.price_per_bedroom } }
        ],
        pageSize: 2
    });
};
 
ko.applyBindings(new PagedGridModel(initialData));
});

    });
    
    


 


</script>
<?php echo $this->Html->script('jquery.noisy.min'); ?>
<script>
$('body').noisy({
    'intensity' : 1, 
    'size' : 200, 
    'opacity' : 0.08, 
    'fallback' : '', 
    'monochrome' : true
}).css('background-color', '#eeecec');
</script>

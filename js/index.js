$(document).ready(function(){
  // Set nav link as active when that page is being displayed
  var current = location.pathname;
    $('li a').each(function(){
      var $this = $(this);
      if($this.attr('href').indexOf(current) !== -1){
        $this.addClass('active');
      }
    })
});
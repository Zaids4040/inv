<!-- Core JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<!-- Material Dashboard Core JS -->
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap-material-design.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

<!-- Plugin JS Files -->
<script src="assets/js/plugins/moment.min.js"></script>
<script src="assets/js/plugins/sweetalert2.js"></script>
<script src="assets/js/plugins/jquery.validate.min.js"></script>
<script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
<script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
<script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="assets/js/plugins/bootstrap-tagsinput.js"></script>
<script src="assets/js/plugins/jasny-bootstrap.min.js"></script>
<script src="assets/js/plugins/fullcalendar.min.js"></script>
<script src="assets/js/plugins/jquery-jvectormap.js"></script>
<script src="assets/js/plugins/nouislider.min.js"></script>
<script src="assets/js/plugins/arrive.min.js"></script>
<script src="assets/js/plugins/chartist.min.js"></script>
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<script src="assets/js/material-dashboard.js?v=2.2.0" type="text/javascript"></script>
<script src="assets/demo/demo.js"></script>

<script>
/**
 * Initialize Materialize components and setup event handlers
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize select elements
  const selectElements = document.querySelectorAll('select');
  M.FormSelect.init(selectElements);
  
  // Initialize tooltips with proper selectors
  const tooltipElements = {
    standard: document.querySelector('.tooltipped'),
    secondary: document.querySelector('.tooltippedd'),
    tertiary: document.querySelector('.tooltippeddd')
  };
  
  // Initialize each tooltip if the element exists
  Object.values(tooltipElements).forEach(element => {
    if (element) M.Tooltip.init(element, {});
  });
  
  // Initialize collapsible elements
  const collapsibles = document.querySelectorAll('.collapsible');
  if (collapsibles.length) {
    M.Collapsible.init(collapsibles, {
      accordion: false
    });
  }
});

/**
 * Delete confirmation handler
 */
function delwork() {
  const yesButton = document.getElementById('yesbtn');
  const deleteButton = document.getElementById('delbtn');
  
  if (yesButton && deleteButton) {
    yesButton.style.display = 'block';
    deleteButton.style.display = 'none';
  }
}

/**
 * Dashboard initialization
 */
$(document).ready(function() {
  // Initialize dashboard components
  $().ready(function() {
    const $sidebar = $('.sidebar');
    const $sidebarImgContainer = $sidebar.find('.sidebar-background');
    const $fullPage = $('.full-page');
    const $sidebarResponsive = $('body > .navbar-collapse');
    const windowWidth = $(window).width();
    
    // Dashboard sidebar initialization
    if (windowWidth > 767 && $('.sidebar .sidebar-wrapper .nav li.active a p').html() === 'Dashboard') {
      if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
        $('.fixed-plugin .dropdown').addClass('open');
      }
    }
    
    // Fixed plugin click handlers
    $('.fixed-plugin a').click(function(event) {
      if ($(this).hasClass('switch-trigger')) {
        if (event.stopPropagation) {
          event.stopPropagation();
        } else if (window.event) {
          window.event.cancelBubble = true;
        }
      }
    });
    
    // Color theme handlers
    $('.fixed-plugin .active-color span').click(function() {
      const $fullPageBackground = $('.full-page-background');
      const newColor = $(this).data('color');
      
      $(this).siblings().removeClass('active');
      $(this).addClass('active');
      
      if ($sidebar.length) $sidebar.attr('data-color', newColor);
      if ($fullPage.length) $fullPage.attr('filter-color', newColor);
      if ($sidebarResponsive.length) $sidebarResponsive.attr('data-color', newColor);
    });
    
    // Background color handlers
    $('.fixed-plugin .background-color .badge').click(function() {
      $(this).siblings().removeClass('active');
      $(this).addClass('active');
      
      const newColor = $(this).data('background-color');
      if ($sidebar.length) $sidebar.attr('data-background-color', newColor);
    });
    
    // Image holder handlers
    $('.fixed-plugin .img-holder').click(function() {
      const $fullPageBackground = $('.full-page-background');
      
      $(this).parent('li').siblings().removeClass('active');
      $(this).parent('li').addClass('active');
      
      const newImage = $(this).find("img").attr('src');
      
      if ($sidebarImgContainer.length && $('.switch-sidebar-image input:checked').length) {
        $sidebarImgContainer.fadeOut('fast', function() {
          $sidebarImgContainer.css('background-image', 'url("' + newImage + '")');
          $sidebarImgContainer.fadeIn('fast');
        });
      }
      
      if ($fullPageBackground.length && $('.switch-sidebar-image input:checked').length) {
        const newImageFullPage = $('.fixed-plugin li.active .img-holder').find('img').data('src');
        
        $fullPageBackground.fadeOut('fast', function() {
          $fullPageBackground.css('background-image', 'url("' + newImageFullPage + '")');
          $fullPageBackground.fadeIn('fast');
        });
      }
      
      if ($('.switch-sidebar-image input:checked').length === 0) {
        const newImage = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
        const newImageFullPage = $('.fixed-plugin li.active .img-holder').find('img').data('src');
        
        $sidebarImgContainer.css('background-image', 'url("' + newImage + '")');
        $fullPageBackground.css('background-image', 'url("' + newImageFullPage + '")');
      }
      
      if ($sidebarResponsive.length) {
        $sidebarResponsive.css('background-image', 'url("' + newImage + '")');
      }
    });
    
    // Sidebar image toggle
    $('.switch-sidebar-image input').change(function() {
      const $fullPageBackground = $('.full-page-background');
      const $input = $(this);
      
      if ($input.is(':checked')) {
        if ($sidebarImgContainer.length) {
          $sidebarImgContainer.fadeIn('fast');
          $sidebar.attr('data-image', '#');
        }
        
        if ($fullPageBackground.length) {
          $fullPageBackground.fadeIn('fast');
          $fullPage.attr('data-image', '#');
        }
      } else {
        if ($sidebarImgContainer.length) {
          $sidebar.removeAttr('data-image');
          $sidebarImgContainer.fadeOut('fast');
        }
        
        if ($fullPageBackground.length) {
          $fullPage.removeAttr('data-image');
          $fullPageBackground.fadeOut('fast');
        }
      }
    });
    
    // Mini sidebar toggle
    $('.switch-sidebar-mini input').change(function() {
      const $body = $('body');
      const $input = $(this);
      
      if (md.misc.sidebar_mini_active) {
        $body.removeClass('sidebar-mini');
        md.misc.sidebar_mini_active = false;
        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
      } else {
        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');
        
        setTimeout(function() {
          $body.addClass('sidebar-mini');
          md.misc.sidebar_mini_active = true;
        }, 300);
      }
      
      // Simulate window resize for chart updates
      const simulateWindowResize = setInterval(function() {
        window.dispatchEvent(new Event('resize'));
      }, 180);
      
      // Stop simulation after animations complete
      setTimeout(function() {
        clearInterval(simulateWindowResize);
      }, 1000);
    });
  });
  
  // Initialize dashboard page charts
  if (typeof md !== 'undefined' && typeof md.initDashboardPageCharts === 'function') {
    md.initDashboardPageCharts();
  }
});
</script>
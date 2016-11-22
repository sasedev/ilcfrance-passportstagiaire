$(function() {
	function fixScroll() {
		if (typeof $.fn.slimScroll == 'undefined') {
			if (window.console) {
				window.console
						.error("Error: the fixed layout requires the slimscroll plugin!");
			}
			return;
		}
		var headerHeight = 0;
		var footerHeight = 0;
		if ($(".main-header>.navbar.navbar-fixed-top").length) {
			headerHeight = $(".main-header>.navbar.navbar-fixed-top").height();
		}
		if ($(".site-footer-fixed").length) {
			footerHeight = $(".site-footer-fixed").height();
		}
		if ($(".sidebar").length) {
			$(".sidebar").slimScroll({
				destroy : true
			}).height("auto");

			$(".sidebar")
					.slimscroll(
							{
								height : ($(window).height() - headerHeight - footerHeight)
										+ "px",
								color : "#00b300",
								size : "5px"
							});
		}
		if ($(".content-wrapper").length) {
			$(".content-wrapper").slimScroll({
				destroy : true
			}).height("auto");

			$(".content-wrapper")
					.slimscroll(
							{
								height : ($(window).height() - headerHeight - footerHeight)
										+ "px",
								color : "#00b300",
								size : "10px"
							});
		}
		if ($(".dashboard-content-wrapper").length) {
			$(".dashboard-content-wrapper").slimScroll({
				destroy : true
			}).height("auto");

			$(".dashboard-content-wrapper")
					.slimscroll(
							{
								height : ($(window).height() - headerHeight - footerHeight)
										+ "px",
								color : "#00b300",
								size : "10px"
							});
		}
	}
	function tree(menu) {
		var animationSpeed = 500;
		$(document).off('click', menu + ' li a').on(
				'click',
				menu + ' li a',
				function(e) {
					// Get the clicked link and the next element
					var $this = $(this);
					var checkElement = $this.next();

					// Check if the next element is a menu and is visible
					if ((checkElement.is('.treeview-menu'))
							&& (checkElement.is(':visible'))
							&& (!$('body').hasClass('sidebar-collapse'))) {
						// Close the menu
						checkElement.slideUp(animationSpeed, function() {
							checkElement.removeClass('menu-open');
							// Fix the layout in case the sidebar stretches over
							// the height of the window
							// _this.layout.fix();
						});
						checkElement.parent("li").removeClass("active");
					}
					// If the menu is not visible
					else if ((checkElement.is('.treeview-menu'))
							&& (!checkElement.is(':visible'))) {
						// Get the parent menu
						var parent = $this.parents('ul').first();
						// Close all open menus within the parent
						var ul = parent.find('ul:visible').slideUp(
								animationSpeed);
						// Remove the menu-open class from the parent
						ul.removeClass('menu-open');
						// Get the parent li
						var parent_li = $this.parent("li");

						// Open the target menu and add the menu-open class
						checkElement.slideDown(animationSpeed, function() {
							// Add the class active to the parent li
							checkElement.addClass('menu-open');
							parent.find('li.active').removeClass('active');
							parent_li.addClass('active');
							// Fix the layout in case the sidebar stretches over
							// the height of the window
							//fixScroll();
						});
					}
					// if this isn't a link, prevent the page from being
					// redirected
					if (checkElement.is('.treeview-menu')) {
						e.preventDefault();
					}
				});
	}

	fixScroll();
	tree('.sidebar');
	$(window).resize(function() {
		fixScroll();
	});
	
	$('input[type="checkbox"], input[type="radio"]')
	.iCheck({
		checkboxClass : 'icheckbox_flat-red',
		radioClass : 'iradio_flat-red'
	});
	$(".select2").select2({theme: "bootstrap"});
	$(".duallistbox").bootstrapDualListbox({
		moveOnSelect : false
	});
	$(".fileinput").fileinput({'showUpload':false});
	$('.datetimepicker').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',
		showClose: true
	});
	
});
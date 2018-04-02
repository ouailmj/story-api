/* ------------------------------------------------------------------------------
*
*  # Template JS core
*
*  Core JS file with default functionality configuration
*
*  Version: 1.2
*  Latest update: Dec 11, 2015
*
* ---------------------------------------------------------------------------- */


// Allow CSS transitions when page is loaded
$(window).on('load', function() {
    $('body').removeClass('no-transitions');
});


$(function() {

    // Disable CSS transitions on page load
    $('body').addClass('no-transitions');



    // ========================================
    //
    // Content area height
    //
    // ========================================


    // Calculate min height
    function containerHeight() {
        var availableHeight = $(window).height() - $('.page-container').offset().top - $('.navbar-fixed-bottom').outerHeight();

        $('.page-container').attr('style', 'min-height:' + availableHeight + 'px');
    }

    // Initialize
    containerHeight();




    // ========================================
    //
    // Heading elements
    //
    // ========================================


    // Heading elements toggler
    // -------------------------

    // Add control button toggler to page and panel headers if have heading elements
    $('.panel-footer').has('> .heading-elements:not(.not-collapsible)').prepend('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');
    $('.page-title, .panel-title').parent().has('> .heading-elements:not(.not-collapsible)').children('.page-title, .panel-title').append('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');


    // Toggle visible state of heading elements
    $('.page-title .heading-elements-toggle, .panel-title .heading-elements-toggle').on('click', function() {
        $(this).parent().parent().toggleClass('has-visible-elements').children('.heading-elements').toggleClass('visible-elements');
    });
    $('.panel-footer .heading-elements-toggle').on('click', function() {
        $(this).parent().toggleClass('has-visible-elements').children('.heading-elements').toggleClass('visible-elements');
    });



    // Breadcrumb elements toggler
    // -------------------------

    // Add control button toggler to breadcrumbs if has elements
    $('.breadcrumb-line').has('.breadcrumb-elements').prepend('<a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>');


    // Toggle visible state of breadcrumb elements
    $('.breadcrumb-elements-toggle').on('click', function() {
        $(this).parent().children('.breadcrumb-elements').toggleClass('visible-elements');
    });




    // ========================================
    //
    // Navbar
    //
    // ========================================


    // Navbar navigation
    // -------------------------

    // Prevent dropdown from closing on click
    $(document).on('click', '.dropdown-content', function (e) {
        e.stopPropagation();
    });

    // Disabled links
    $('.navbar-nav .disabled a').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Show tabs inside dropdowns
    $('.dropdown-content a[data-toggle="tab"]').on('click', function (e) {
        $(this).tab('show');
    });



    // Drill down menu
    // ------------------------------

    // If menu has child levels, add selector class
    $('.menu-list').find('li').has('ul').parents('.menu-list').addClass('has-children');

    // Attach drill down menu to menu list with child levels
    $('.has-children').dcDrilldown({
        defaultText: 'Back to parent',
        saveState: true
    });




    // ========================================
    //
    // Element controls
    //
    // ========================================


    // Reload elements
    // -------------------------

    // Panels
    $('.panel [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    });


    // Sidebar categories
    $('.category-title [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#000',
                opacity: 0.5,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #000'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    }); 


    // Light sidebar categories
    $('.sidebar-default .category-title [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    }); 



    // Collapse elements
    // -------------------------

    //
    // Sidebar categories
    //

    // Hide if collapsed by default
    $('.category-collapsed').children('.category-content').hide();


    // Rotate icon if collapsed by default
    $('.category-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.category-title [data-action=collapse]').click(function (e) {
        e.preventDefault();
        var $categoryCollapse = $(this).parent().parent().parent().nextAll();
        $(this).parents('.category-title').toggleClass('category-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // adjust page height

        $categoryCollapse.slideToggle(150);
    });


    //
    // Panels
    //

    // Hide if collapsed by default
    $('.panel-collapsed').children('.panel-heading').nextAll().hide();


    // Rotate icon if collapsed by default
    $('.panel-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.panel [data-action=collapse]').click(function (e) {
        e.preventDefault();
        var $panelCollapse = $(this).parent().parent().parent().parent().nextAll();
        $(this).parents('.panel').toggleClass('panel-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // recalculate page height

        $panelCollapse.slideToggle(150);
    });



    // Remove elements
    // -------------------------

    // Panels
    $('.panel [data-action=close]').click(function (e) {
        e.preventDefault();
        var $panelClose = $(this).parent().parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $panelClose.slideUp(150, function() {
            $(this).remove();
        });
    });


    // Sidebar categories
    $('.category-title [data-action=close]').click(function (e) {
        e.preventDefault();
        var $categoryClose = $(this).parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $categoryClose.slideUp(150, function() {
            $(this).remove();
        });
    });




    // ========================================
    //
    // Main navigation
    //
    // ========================================


    // Main navigation
    // -------------------------

    // Add 'active' class to parent list item in all levels
    $('.navigation').find('li.active').parents('li').addClass('active');

    // Hide all nested lists
    $('.navigation').find('li').not('.active, .category-title').has('ul').children('ul').addClass('hidden-ul');

    // Highlight children links
    $('.navigation').find('li').has('ul').children('a').addClass('has-ul');

    // Add active state to all dropdown parent levels
    $('.dropdown-menu:not(.dropdown-content), .dropdown-menu:not(.dropdown-content) .dropdown-submenu').has('li.active').addClass('active').parents('.navbar-nav .dropdown:not(.language-switch), .navbar-nav .dropup:not(.language-switch)').addClass('active');

    

    // Main navigation tooltips positioning
    // -------------------------

    // Left sidebar
    $('.navigation-main > .navigation-header > i').tooltip({
        placement: 'right',
        container: 'body'
    });



    // Collapsible functionality
    // -------------------------

    // Main navigation
    $('.navigation-main').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).toggleClass('active').children('ul').slideToggle(250);

        // Accordion
        if ($('.navigation-main').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(250);
        }
    });

        
    // Alternate navigation
    $('.navigation-alt').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(200);

        // Accordion
        if ($('.navigation-alt').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(200);
        }
    }); 




    // ========================================
    //
    // Sidebars
    //
    // ========================================


    // Mini sidebar
    // -------------------------

    // Toggle mini sidebar
    $('.sidebar-main-toggle').on('click', function (e) {
        e.preventDefault();

        // Toggle min sidebar class
        $('body').toggleClass('sidebar-xs');
    });



    // Sidebar controls
    // -------------------------

    // Disable click in disabled navigation items
    $(document).on('click', '.navigation .disabled a', function (e) {
        e.preventDefault();
    });


    // Adjust page height on sidebar control button click
    $(document).on('click', '.sidebar-control', function (e) {
        containerHeight();
    });


    // Hide main sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-main-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-main-hidden');
    });


    // Toggle second sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-secondary-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-secondary-hidden');
    });


    // Hide all sidebars
    $(document).on('click', '.sidebar-all-hide', function (e) {
        e.preventDefault();

        $('body').toggleClass('sidebar-all-hidden');
    });



    //
    // Opposite sidebar
    //

    // Collapse main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-toggle', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Make main sidebar mini
            $('body').addClass('sidebar-xs');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        }
        else {

            // Make main sidebar default
            $('body').removeClass('sidebar-xs');
        }
    });


    // Hide main sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-main-hide', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');
        
        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide main sidebar
            $('body').addClass('sidebar-main-hidden');
        }
        else {

            // Show main sidebar
            $('body').removeClass('sidebar-main-hidden');
        }
    });


    // Hide secondary sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-secondary-hide', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide secondary
            $('body').addClass('sidebar-secondary-hidden');

        }
        else {

            // Show secondary
            $('body').removeClass('sidebar-secondary-hidden');
        }
    });


    // Hide all sidebars if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-hide', function (e) {
        e.preventDefault();

        // Toggle sidebars visibility
        $('body').toggleClass('sidebar-all-hidden');

        // If hidden
        if ($('body').hasClass('sidebar-all-hidden')) {

            // Show opposite
            $('body').addClass('sidebar-opposite-visible');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        }
        else {

            // Hide opposite
            $('body').removeClass('sidebar-opposite-visible');
        }
    });


    // Keep the width of the main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-fix', function (e) {
        e.preventDefault();

        // Toggle opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');
    });



    // Mobile sidebar controls
    // -------------------------

    // Toggle main sidebar
    $('.sidebar-mobile-main-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite');
    });


    // Toggle secondary sidebar
    $('.sidebar-mobile-secondary-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-secondary').removeClass('sidebar-mobile-main sidebar-mobile-opposite');
    });


    // Toggle opposite sidebar
    $('.sidebar-mobile-opposite-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-opposite').removeClass('sidebar-mobile-main sidebar-mobile-secondary');
    });



    // Mobile sidebar setup
    // -------------------------

    $(window).on('resize', function() {
        setTimeout(function() {
            containerHeight();
            
            if($(window).width() <= 768) {

                // Add mini sidebar indicator
                $('body').addClass('sidebar-xs-indicator');

                // Place right sidebar before content
                $('.sidebar-opposite').prependTo('.page-content');

                // Remove nicescroll on mobiles
                $('.menu-list').getNiceScroll().remove();
                $(".menu-list").removeAttr('style').removeAttr('tabindex');

                // Add mouse events for dropdown submenus
                $('.dropdown-submenu').on('mouseenter', function() {
                    $(this).children('.dropdown-menu').addClass('show');
                }).on('mouseleave', function() {
                    $(this).children('.dropdown-menu').removeClass('show');
                });
            }
            else {

                // Remove mini sidebar indicator
                $('body').removeClass('sidebar-xs-indicator');

                // Revert back right sidebar
                $('.sidebar-opposite').insertAfter('.content-wrapper');

                // Remove all mobile sidebar classes
                $('body').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-opposite');

                // Initialize nicescroll on tablets+
                $(".menu-list").niceScroll({
                    mousescrollstep: 100,
                    cursorcolor: '#ccc',
                    cursorborder: '',
                    cursorwidth: 3,
                    hidecursordelay: 200,
                    autohidemode: 'scroll',
                    railpadding: { right: 0.5 }
                });

                // Remove visibility of heading elements on desktop
                $('.page-header-content, .panel-heading, .panel-footer').removeClass('has-visible-elements');
                $('.heading-elements').removeClass('visible-elements');

                // Disable appearance of dropdown submenus
                $('.dropdown-submenu').children('.dropdown-menu').removeClass('show');
            }
        }, 100);
    }).resize();




    // ========================================
    //
    // Other code
    //
    // ========================================


    // Plugins
    // -------------------------

    // Popover
    $('[data-popup="popover"]').popover();


    // Tooltip
    $('[data-popup="tooltip"]').tooltip();

    // Switchery toggles
    // ------------------------------

    var switches = Array.prototype.slice.call(document.querySelectorAll('.switch'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
    });



    // Daterange picker
    // ------------------------------

    //
    // Basic display
    //

    $('.daterange-ranges').daterangepicker(
        {
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2016',
            dateLimit: { days: 60 },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last 7 Days': [moment().subtract('days', 6), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            opens: 'left',
            applyClass: 'btn-small bg-slate-600 btn-block',
            cancelClass: 'btn-small btn-default btn-block',
            format: 'MM/DD/YYYY'
        },
        function(start, end) {
            $('.daterange-ranges span').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
        }
    );

    $('.daterange-ranges span').html(moment().subtract('days', 29).format('MMMM D') + ' - ' + moment().format('MMMM D'));



    // Traffic sources stream chart
    // ------------------------------

    trafficSources('#traffic-sources', 330); // initialize chart

    // Chart setup
    function trafficSources(element, height) {


        // Basic setup
        // ------------------------------

        // Define main variables
        var d3Container = d3.select(element),
            margin = {top: 5, right: 50, bottom: 40, left: 50},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom,
            tooltipOffset = 30;

        // Tooltip
        var tooltip = d3Container
            .append("div")
            .attr("class", "d3-tip e")
            .style("display", "none")

        // Format date
        var format = d3.time.format("%m/%d/%y %H:%M");
        var formatDate = d3.time.format("%H:%M");

        // Colors
        var colorrange = ['#03A9F4', '#29B6F6', '#4FC3F7', '#81D4FA', '#B3E5FC', '#E1F5FE'];



        // Construct scales
        // ------------------------------

        // Horizontal
        var x = d3.time.scale().range([0, width]);

        // Vertical
        var y = d3.scale.linear().range([height, 0]);

        // Colors
        var z = d3.scale.ordinal().range(colorrange);



        // Create axes
        // ------------------------------

        // Horizontal
        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom")
            .ticks(d3.time.hours, 4)
            .innerTickSize(4)
            .tickPadding(8)
            .tickFormat(d3.time.format("%H:%M")); // Display hours and minutes in 24h format

        // Left vertical
        var yAxis = d3.svg.axis()
            .scale(y)
            .ticks(6)
            .innerTickSize(4)
            .outerTickSize(0)
            .tickPadding(8)
            .tickFormat(function (d) { return (d/1000) + "k"; });

        // Right vertical
        var yAxis2 = yAxis;

        // Dash lines
        var gridAxis = d3.svg.axis()
            .scale(y)
            .orient("left")
            .ticks(6)
            .tickPadding(8)
            .tickFormat("")
            .tickSize(-width, 0, 0);



        // Create chart
        // ------------------------------

        // Container
        var container = d3Container.append("svg")

        // SVG element
        var svg = container
            .attr('width', width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");



        // Construct chart layout
        // ------------------------------

        // Stack
        var stack = d3.layout.stack()
            .offset("silhouette")
            .values(function(d) { return d.values; })
            .x(function(d) { return d.date; })
            .y(function(d) { return d.value; });

        // Nest
        var nest = d3.nest()
            .key(function(d) { return d.key; });

        // Area
        var area = d3.svg.area()
            .interpolate("cardinal")
            .x(function(d) { return x(d.date); })
            .y0(function(d) { return y(d.y0); })
            .y1(function(d) { return y(d.y0 + d.y); });



        // Load data
        // ------------------------------

        d3.csv("assets/demo_data/dashboard/traffic_sources.csv", function (error, data) {

            // Pull out values
            data.forEach(function (d) {
                d.date = format.parse(d.date);
                d.value = +d.value;
            });

            // Stack and nest layers
            var layers = stack(nest.entries(data));



            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.extent(data, function(d, i) { return d.date; }));

            // Vertical
            y.domain([0, d3.max(data, function(d) { return d.y0 + d.y; })]);



            // Add grid
            // ------------------------------

            // Horizontal grid. Must be before the group
            svg.append("g")
                .attr("class", "d3-grid-dashed")
                .call(gridAxis);



            //
            // Append chart elements
            //

            // Stream layers
            // ------------------------------

            // Create group
            var group = svg.append('g')
                .attr('class', 'streamgraph-layers-group');

            // And append paths to this group
            var layer = group.selectAll(".streamgraph-layer")
                .data(layers)
                .enter()
                    .append("path")
                    .attr("class", "streamgraph-layer")
                    .attr("d", function(d) { return area(d.values); })                    
                    .style('stroke', '#fff')
                    .style('stroke-width', 0.5)
                    .style("fill", function(d, i) { return z(i); });

            // Add transition
            var layerTransition = layer
                .style('opacity', 0)
                .transition()
                    .duration(750)
                    .delay(function(d, i) { return i * 50; })
                    .style('opacity', 1)



            // Append axes
            // ------------------------------

            //
            // Left vertical
            //

            svg.append("g")
                .attr("class", "d3-axis d3-axis-left d3-axis-solid")
                .call(yAxis.orient("left"));

            // Hide first tick
            d3.select(svg.selectAll('.d3-axis-left .tick text')[0][0])
                .style("visibility", "hidden");


            //
            // Right vertical
            //

            svg.append("g")
                .attr("class", "d3-axis d3-axis-right d3-axis-solid")
                .attr("transform", "translate(" + width + ", 0)")
                .call(yAxis2.orient("right"));

            // Hide first tick
            d3.select(svg.selectAll('.d3-axis-right .tick text')[0][0])
                .style("visibility", "hidden");


            //
            // Horizontal
            //

            var xaxisg = svg.append("g")
                .attr("class", "d3-axis d3-axis-horizontal d3-axis-solid")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            // Add extra subticks for hidden hours
            xaxisg.selectAll(".d3-axis-subticks")
                .data(x.ticks(d3.time.hours), function(d) { return d; })
                .enter()
                .append("line")
                .attr("class", "d3-axis-subticks")
                .attr("y1", 0)
                .attr("y2", 4)
                .attr("x1", x)
                .attr("x2", x);



            // Add hover line and pointer
            // ------------------------------

            // Append group to the group of paths to prevent appearance outside chart area
            var hoverLineGroup = group.append("g")
                .attr("class", "hover-line");

            // Add line
            var hoverLine = hoverLineGroup
                .append("line")
                .attr("y1", 0)
                .attr("y2", height)
                .style('fill', 'none')
                .style('stroke', '#fff')
                .style('stroke-width', 1)
                .style('pointer-events', 'none')
                .style('shape-rendering', 'crispEdges')
                .style("opacity", 0);

            // Add pointer
            var hoverPointer = hoverLineGroup
                .append("rect")
                .attr("x", 2)
                .attr("y", 2)
                .attr("width", 6)
                .attr("height", 6)
                .style('fill', '#03A9F4')
                .style('stroke', '#fff')
                .style('stroke-width', 1)
                .style('shape-rendering', 'crispEdges')
                .style('pointer-events', 'none')
                .style("opacity", 0);



            // Append events to the layers group
            // ------------------------------

            layerTransition.each("end", function() {
                layer
                    .on("mouseover", function (d, i) {
                        svg.selectAll(".streamgraph-layer")
                            .transition()
                            .duration(250)
                            .style("opacity", function (d, j) {
                                return j != i ? 0.75 : 1; // Mute all except hovered
                            });
                    })

                    .on("mousemove", function (d, i) {
                        mouse = d3.mouse(this);
                        mousex = mouse[0];
                        mousey = mouse[1];
                        datearray = [];
                        var invertedx = x.invert(mousex);
                        invertedx = invertedx.getHours();
                        var selected = (d.values);
                        for (var k = 0; k < selected.length; k++) {
                            datearray[k] = selected[k].date
                            datearray[k] = datearray[k].getHours();
                        }
                        mousedate = datearray.indexOf(invertedx);
                        pro = d.values[mousedate].value;


                        // Display mouse pointer
                        hoverPointer
                            .attr("x", mousex - 3)
                            .attr("y", mousey - 6)
                            .style("opacity", 1);

                        hoverLine
                            .attr("x1", mousex)
                            .attr("x2", mousex)
                            .style("opacity", 1);

                        //
                        // Tooltip
                        //

                        // Tooltip data
                        tooltip.html(
                            "<ul class='list-unstyled mb-5'>" +
                                "<li>" + "<div class='text-size-base mt-5 mb-5'><i class='icon-circle-left2 position-left'></i>" + d.key + "</div>" + "</li>" +
                                "<li>" + "Visits: &nbsp;" + "<span class='text-semibold pull-right'>" + pro + "</span>" + "</li>" +
                                "<li>" + "Time: &nbsp; " + "<span class='text-semibold pull-right'>" + formatDate(d.values[mousedate].date) + "</span>" + "</li>" + 
                            "</ul>"
                        )
                        .style("display", "block");

                        // Tooltip arrow
                        tooltip.append('div').attr('class', 'd3-tip-arrow');
                    })

                    .on("mouseout", function (d, i) {

                        // Revert full opacity to all paths
                        svg.selectAll(".streamgraph-layer")
                            .transition()
                            .duration(250)
                            .style("opacity", 1);

                        // Hide cursor pointer
                        hoverPointer.style("opacity", 0);

                        // Hide tooltip
                        tooltip.style("display", "none");

                        hoverLine.style("opacity", 0);
                    });
                });



            // Append events to the chart container
            // ------------------------------

            d3Container
                .on("mousemove", function (d, i) {
                    mouse = d3.mouse(this);
                    mousex = mouse[0];
                    mousey = mouse[1];

                    // Display hover line
                        //.style("opacity", 1);


                    // Move tooltip vertically
                    tooltip.style("top", (mousey - ($('.d3-tip').outerHeight() / 2)) - 2 + "px") // Half tooltip height - half arrow width

                    // Move tooltip horizontally
                    if(mousex >= ($(element).outerWidth() - $('.d3-tip').outerWidth() - margin.right - (tooltipOffset * 2))) {
                        tooltip
                            .style("left", (mousex - $('.d3-tip').outerWidth() - tooltipOffset) + "px") // Change tooltip direction from right to left to keep it inside graph area
                            .attr("class", "d3-tip w");
                    }
                    else {
                        tooltip
                            .style("left", (mousex + tooltipOffset) + "px" )
                            .attr("class", "d3-tip e");
                    }
                });
        });



        // Resize chart
        // ------------------------------

        // Call function on window resize
        $(window).on('resize', resizeStream);

        // Call function on sidebar width change
        $(document).on('click', '.sidebar-control', resizeStream);

        // Resize function
        // 
        // Since D3 doesn't support SVG resize by default,
        // we need to manually specify parts of the graph that need to 
        // be updated on window resize
        function resizeStream() {

            // Layout
            // -------------------------

            // Define width
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;

            // Main svg width
            container.attr("width", width + margin.left + margin.right);

            // Width of appended group
            svg.attr("width", width + margin.left + margin.right);

            // Horizontal range
            x.range([0, width]);


            // Chart elements
            // -------------------------

            // Horizontal axis
            svg.selectAll('.d3-axis-horizontal').call(xAxis);

            // Horizontal axis subticks
            svg.selectAll('.d3-axis-subticks').attr("x1", x).attr("x2", x);

            // Grid lines width
            svg.selectAll(".d3-grid-dashed").call(gridAxis.tickSize(-width, 0, 0))

            // Right vertical axis
            svg.selectAll(".d3-axis-right").attr("transform", "translate(" + width + ", 0)");

            // Area paths
            svg.selectAll('.streamgraph-layer').attr("d", function(d) { return area(d.values); });
        }
    }



    // App sales lines chart
    // ------------------------------

    appSalesLines('#app_sales', 255); // initialize chart

    // Chart setup
    function appSalesLines(element, height) {


        // Basic setup
        // ------------------------------

        // Define main variables
        var d3Container = d3.select(element),
            margin = {top: 5, right: 30, bottom: 30, left: 50},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom;

        // Tooltip
        var tooltip = d3.tip()
            .attr('class', 'd3-tip')
            .html(function (d) {
                return "<ul class='list-unstyled mb-5'>" +
                    "<li>" + "<div class='text-size-base mt-5 mb-5'><i class='icon-circle-left2 position-left'></i>" + d.name + " app" + "</div>" + "</li>" +
                    "<li>" + "Sales: &nbsp;" + "<span class='text-semibold pull-right'>" + d.value + "</span>" + "</li>" +
                    "<li>" + "Revenue: &nbsp; " + "<span class='text-semibold pull-right'>" + "$" + (d.value * 25).toFixed(2) + "</span>" + "</li>" + 
                "</ul>";
            });

        // Format date
        var parseDate = d3.time.format("%Y/%m/%d").parse,
            formatDate = d3.time.format("%b %d, '%y");

        // Line colors
        var scale = ["#4CAF50", "#FF5722", "#5C6BC0"],
            color = d3.scale.ordinal().range(scale);



        // Create chart
        // ------------------------------

        // Container
        var container = d3Container.append('svg');

        // SVG element
        var svg = container
            .attr('width', width + margin.left + margin.right)
            .attr('height', height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                .call(tooltip);



        // Add date range switcher
        // ------------------------------

        // Menu
        var menu = $("#select_date").multiselect({
            buttonClass: 'btn btn-link text-semibold',
            enableHTML: true,
            dropRight: true,
            onChange: function() { change(), $.uniform.update(); },
            buttonText: function (options, element) {
                var selected = '';
                options.each(function() {
                    selected += $(this).html() + ', ';
                });
                return '<span class="status-mark border-warning position-left"></span>' + selected.substr(0, selected.length -2);
            }
        });

        // Radios
        $(".multiselect-container input").uniform({ radioClass: 'choice' });



        // Load data
        // ------------------------------

        d3.csv("assets/demo_data/dashboard/app_sales.csv", function(error, data) {
            formatted = data;
            redraw();
        });



        // Construct layout
        // ------------------------------

        // Add events
        var altKey;
        d3.select(window)
            .on("keydown", function() { altKey = d3.event.altKey; })
            .on("keyup", function() { altKey = false; });
    
        // Set terms of transition on date change   
        function change() {
          d3.transition()
              .duration(altKey ? 7500 : 500)
              .each(redraw);
        }



        // Main chart drawing function
        // ------------------------------

        function redraw() { 

            // Construct chart layout
            // ------------------------------

            // Create data nests
            var nested = d3.nest()
                .key(function(d) { return d.type; })
                .map(formatted)
            
            // Get value from menu selection
            // the option values correspond
            //to the [type] value we used to nest the data  
            var series = menu.val();
            
            // Only retrieve data from the selected series using nest
            var data = nested[series];
            
            // For object constancy we will need to set "keys", one for each type of data (column name) exclude all others.
            color.domain(d3.keys(data[0]).filter(function(key) { return (key !== "date" && key !== "type"); }));

            // Setting up color map
            var linedata = color.domain().map(function(name) {
                return {
                            name: name,
                            values: data.map(function(d) {
                                return {name: name, date: parseDate(d.date), value: parseFloat(d[name], 10)};
                            })
                        };
                    });

            // Draw the line
            var line = d3.svg.line()
                .x(function(d) { return x(d.date); })
                .y(function(d) { return y(d.value); })
                .interpolate('cardinal');



            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.time.scale()
                .domain([
                    d3.min(linedata, function(c) { return d3.min(c.values, function(v) { return v.date; }); }),
                    d3.max(linedata, function(c) { return d3.max(c.values, function(v) { return v.date; }); })
                ])
                .range([0, width]);

            // Vertical
            var y = d3.scale.linear()
                .domain([
                    d3.min(linedata, function(c) { return d3.min(c.values, function(v) { return v.value; }); }),
                    d3.max(linedata, function(c) { return d3.max(c.values, function(v) { return v.value; }); })
                ])
                .range([height, 0]);



            // Create axes
            // ------------------------------

            // Horizontal
            var xAxis = d3.svg.axis()
                .scale(x)
                .orient("bottom")
                .tickPadding(8)
                .ticks(d3.time.days)
                .innerTickSize(4)
                .tickFormat(d3.time.format("%a")); // Display hours and minutes in 24h format

            // Vertical
            var yAxis = d3.svg.axis()
                .scale(y)
                .orient("left")
                .ticks(6)
                .tickSize(0 -width)
                .tickPadding(8);
            


            //
            // Append chart elements
            //

            // Append axes
            // ------------------------------

            // Horizontal
            svg.append("g")
                .attr("class", "d3-axis d3-axis-horizontal d3-axis-solid")
                .attr("transform", "translate(0," + height + ")");

            // Vertical
            svg.append("g")
                .attr("class", "d3-axis d3-axis-vertical d3-axis-transparent");



            // Append lines
            // ------------------------------

            // Bind the data
            var lines = svg.selectAll(".lines")
                .data(linedata)
         
            // Append a group tag for each line
            var lineGroup = lines
                .enter()
                .append("g")
                    .attr("class", "lines")
                    .attr('id', function(d){ return d.name + "-line"; });

            // Append the line to the graph
            lineGroup.append("path")
                .attr("class", "d3-line d3-line-medium")
                .style("stroke", function(d) { return color(d.name); })
                .style('opacity', 0)
                .attr("d", function(d) { return line(d.values[0]); })
                .transition()
                    .duration(500)
                    .delay(function(d, i) { return i * 200; })
                    .style('opacity', 1);
          


            // Append circles
            // ------------------------------

            var circles = lines.selectAll("circle")
                .data(function(d) { return d.values; })
                .enter()
                .append("circle")
                    .attr("class", "d3-line-circle d3-line-circle-medium")
                    .attr("cx", function(d,i){return x(d.date)})
                    .attr("cy",function(d,i){return y(d.value)})
                    .attr("r", 3)
                    .style('fill', '#fff')
                    .style("stroke", function(d) { return color(d.name); });

            // Add transition
            circles
                .style('opacity', 0)
                .transition()
                    .duration(500)
                    .delay(500)
                    .style('opacity', 1);



            // Append tooltip
            // ------------------------------

            // Add tooltip on circle hover
            circles
                .on("mouseover", function (d) {
                    tooltip.offset([-15, 0]).show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })
                .on("mouseout", function (d) {
                    tooltip.hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                });

            // Change tooltip direction of first point
            // to always keep it inside chart, useful on mobiles
            lines.each(function (d) { 
                d3.select(d3.select(this).selectAll('circle')[0][0])
                    .on("mouseover", function (d) {
                        tooltip.offset([0, 15]).direction('e').show(d);

                        // Animate circle radius
                        d3.select(this).transition().duration(250).attr('r', 4);
                    })
                    .on("mouseout", function (d) {
                        tooltip.direction('n').hide(d);

                        // Animate circle radius
                        d3.select(this).transition().duration(250).attr('r', 3);
                    });
            })

            // Change tooltip direction of last point
            // to always keep it inside chart, useful on mobiles
            lines.each(function (d) { 
                d3.select(d3.select(this).selectAll('circle')[0][d3.select(this).selectAll('circle').size() - 1])
                    .on("mouseover", function (d) {
                        tooltip.offset([0, -15]).direction('w').show(d);

                        // Animate circle radius
                        d3.select(this).transition().duration(250).attr('r', 4);
                    })
                    .on("mouseout", function (d) {
                        tooltip.direction('n').hide(d);

                        // Animate circle radius
                        d3.select(this).transition().duration(250).attr('r', 3);
                    })
            })



            // Update chart on date change
            // ------------------------------

            // Set variable for updating visualization
            var lineUpdate = d3.transition(lines);
            
            // Update lines
            lineUpdate.select("path")
                .attr("d", function(d, i) { return line(d.values); });

            // Update circles
            lineUpdate.selectAll("circle")
                .attr("cy",function(d,i){return y(d.value)})
                .attr("cx", function(d,i){return x(d.date)});

            // Update vertical axes
            d3.transition(svg)
                .select(".d3-axis-vertical")
                .call(yAxis);   

            // Update horizontal axes
            d3.transition(svg)
                .select(".d3-axis-horizontal")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);



            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', appSalesResize);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', appSalesResize);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function appSalesResize() {

                // Layout
                // -------------------------

                // Define width
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;

                // Main svg width
                container.attr("width", width + margin.left + margin.right);

                // Width of appended group
                svg.attr("width", width + margin.left + margin.right);

                // Horizontal range
                x.range([0, width]);

                // Vertical range
                y.range([height, 0]);


                // Chart elements
                // -------------------------

                // Horizontal axis
                svg.select('.d3-axis-horizontal').call(xAxis);

                // Vertical axis
                svg.select('.d3-axis-vertical').call(yAxis.tickSize(0-width));

                // Lines
                svg.selectAll('.d3-line').attr("d", function(d, i) { return line(d.values); });

                // Circles
                svg.selectAll('.d3-line-circle').attr("cx", function(d,i){return x(d.date)})
            }
        }
    }




    // Monthly app sales area chart
    // ------------------------------

    monthlySalesArea("#monthly-sales-stats", 100, '#4DB6AC'); // initialize chart

    // Chart setup
    function monthlySalesArea(element, height, color) {


        // Basic setup
        // ------------------------------

        // Define main variables
        var d3Container = d3.select(element),
            margin = {top: 20, right: 35, bottom: 40, left: 35},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom;

        // Date and time format
        var parseDate = d3.time.format( '%Y-%m-%d' ).parse,
            bisectDate = d3.bisector(function(d) { return d.date; }).left,
            formatDate = d3.time.format("%b %d");



        // Create SVG
        // ------------------------------

        // Container
        var container = d3Container.append('svg');

        // SVG element
        var svg = container
            .attr('width', width + margin.left + margin.right)
            .attr('height', height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")



        // Construct chart layout
        // ------------------------------

        // Area
        var area = d3.svg.area()
            .x(function(d) { return x(d.date); })
            .y0(height)
            .y1(function(d) { return y(d.value); })
            .interpolate('monotone')


        // Construct scales
        // ------------------------------

        // Horizontal
        var x = d3.time.scale().range([0, width ]);

        // Vertical
        var y = d3.scale.linear().range([height, 0]);


        // Create axes
        // ------------------------------

        // Horizontal
        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom")
            .ticks(d3.time.days, 6)
            .innerTickSize(4)
            .tickPadding(8)
            .tickFormat(d3.time.format("%b %d"));


        // Load data
        // ------------------------------

        d3.json("assets/demo_data/dashboard/monthly_sales.json", function (error, data) {

            // Show what's wrong if error
            if (error) return console.error(error);

            // Pull out values
            data.forEach(function (d) {
                d.date = parseDate(d.date);
                d.value = +d.value;
            });

            // Get the maximum value in the given array
            var maxY = d3.max(data, function(d) { return d.value; });

            // Reset start data for animation
            var startData = data.map(function(datum) {
                return {
                    date: datum.date,
                    value: 0
                };
            });


            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.extent(data, function(d, i) { return d.date; }));

            // Vertical
            y.domain([0, d3.max( data, function(d) { return d.value; })]);



            //
            // Append chart elements
            //

            // Append axes
            // -------------------------

            // Horizontal
            var horizontalAxis = svg.append("g")
                .attr("class", "d3-axis d3-axis-horizontal d3-axis-solid")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            // Add extra subticks for hidden hours
            horizontalAxis.selectAll(".d3-axis-subticks")
                .data(x.ticks(d3.time.days), function(d) { return d; })
                .enter()
                    .append("line")
                    .attr("class", "d3-axis-subticks")
                    .attr("y1", 0)
                    .attr("y2", 4)
                    .attr("x1", x)
                    .attr("x2", x);



            // Append area
            // -------------------------

            // Add area path
            svg.append("path")
                .datum(data)
                .attr("class", "d3-area")
                .attr("d", area)
                .style('fill', color)
                .transition() // begin animation
                    .duration(1000)
                    .attrTween('d', function() {
                        var interpolator = d3.interpolateArray(startData, data);
                        return function (t) {
                            return area(interpolator (t));
                        }
                    });



            // Append crosshair and tooltip
            // -------------------------

            //
            // Line
            //

            // Line group
            var focusLine = svg.append("g")
                .attr("class", "d3-crosshair-line")
                .style("display", "none");

            // Line element
            focusLine.append("line")
                .attr("class", "vertical-crosshair")
                .attr("y1", 0)
                .attr("y2", -maxY)
                .style("stroke", "#e5e5e5")
                .style('shape-rendering', 'crispEdges')


            //
            // Pointer
            //

            // Pointer group
            var focusPointer = svg.append("g")
                .attr("class", "d3-crosshair-pointer")
                .style("display", "none");

            // Pointer element
            focusPointer.append("circle")
                .attr("r", 3)
                .style("fill", "#fff")
                .style('stroke', color)
                .style('stroke-width', 1)


            //
            // Text
            //

            // Text group
            var focusText = svg.append("g")
                .attr("class", "d3-crosshair-text")
                .style("display", "none");

            // Text element
            focusText.append("text")
                .attr("dy", -10)
                .style('font-size', 12);


            //
            // Overlay with events
            //

            svg.append("rect")
                .attr("class", "d3-crosshair-overlay")
                .style('fill', 'none')
                .style('pointer-events', 'all')
                .attr("width", width)
                .attr("height", height)
                    .on("mouseover", function() {
                        focusPointer.style("display", null);        
                        focusLine.style("display", null)
                        focusText.style("display", null);
                    })
                    .on("mouseout", function() {
                        focusPointer.style("display", "none"); 
                        focusLine.style("display", "none");
                        focusText.style("display", "none");
                    })
                    .on("mousemove", mousemove);


            // Display tooltip on mousemove
            function mousemove() {

                // Define main variables
                var mouse = d3.mouse(this),
                    mousex = mouse[0],
                    mousey = mouse[1],
                    x0 = x.invert(mousex),
                    i = bisectDate(data, x0),
                    d0 = data[i - 1],
                    d1 = data[i],
                    d = x0 - d0.date > d1.date - x0 ? d1 : d0;

                // Move line
                focusLine.attr("transform", "translate(" + x(d.date) + "," + height + ")");

                // Move pointer
                focusPointer.attr("transform", "translate(" + x(d.date) + "," + y(d.value) + ")");

                // Reverse tooltip at the end point
                if(mousex >= (d3Container.node().getBoundingClientRect().width - focusText.select('text').node().getBoundingClientRect().width - margin.right - margin.left)) {
                    focusText.select("text").attr('text-anchor', 'end').attr("x", function () { return (x(d.date) - 15) + "px" }).text(formatDate(d.date) + " - " + d.value + " sales");
                }
                else {
                    focusText.select("text").attr('text-anchor', 'start').attr("x", function () { return (x(d.date) + 15) + "px" }).text(formatDate(d.date) + " - " + d.value + " sales");
                }
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', monthlySalesAreaResize);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', monthlySalesAreaResize);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function monthlySalesAreaResize() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width + margin.left + margin.right);

                // Width of appended group
                svg.attr("width", width + margin.left + margin.right);


                // Axes
                // -------------------------

                // Horizontal range
                x.range([0, width]);

                // Horizontal axis
                svg.selectAll('.d3-axis-horizontal').call(xAxis);

                // Horizontal axis subticks
                svg.selectAll('.d3-axis-subticks').attr("x1", x).attr("x2", x);


                // Chart elements
                // -------------------------

                // Area path
                svg.selectAll('.d3-area').datum( data ).attr("d", area);

                // Crosshair
                svg.selectAll('.d3-crosshair-overlay').attr("width", width);
            }
        });
    }




    // Messages area chart
    // ------------------------------

    messagesArea("#messages-stats", 40, '#5C6BC0'); // initialize chart

    // Chart setup
    function messagesArea(element, height, color) {


        // Basic setup
        // ------------------------------

        // Define main variables
        var d3Container = d3.select(element),
            margin = {top: 0, right: 0, bottom: 0, left: 0},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom;

        // Date and time format
        var parseDate = d3.time.format( '%Y-%m-%d' ).parse;


        // Create SVG
        // ------------------------------

        // Container
        var container = d3Container.append('svg');

        // SVG element
        var svg = container
            .attr('width', width + margin.left + margin.right)
            .attr('height', height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")


        // Construct chart layout
        // ------------------------------

        // Area
        var area = d3.svg.area()
            .x(function(d) { return x(d.date); })
            .y0(height)
            .y1(function(d) { return y(d.value); })
            .interpolate('monotone')


        // Construct scales
        // ------------------------------

        // Horizontal
        var x = d3.time.scale().range([0, width ]);

        // Vertical
        var y = d3.scale.linear().range([height, 0]);


        // Load data
        // ------------------------------

        d3.json("assets/demo_data/dashboard/monthly_sales.json", function (error, data) {

            // Show what's wrong if error
            if (error) return console.error(error);

            // Pull out values
            data.forEach(function (d) {
                d.date = parseDate(d.date);
                d.value = +d.value;
            });

            // Get the maximum value in the given array
            var maxY = d3.max(data, function(d) { return d.value; });

            // Reset start data for animation
            var startData = data.map(function(datum) {
                return {
                    date: datum.date,
                    value: 0
                };
            });


            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.extent(data, function(d, i) { return d.date; }));

            // Vertical
            y.domain([0, d3.max( data, function(d) { return d.value; })]);



            //
            // Append chart elements
            //

            // Add area path
            svg.append("path")
                .datum(data)
                .attr("class", "d3-area")
                .style('fill', color)
                .attr("d", area)
                .transition() // begin animation
                    .duration(1000)
                    .attrTween('d', function() {
                        var interpolator = d3.interpolateArray(startData, data);
                        return function (t) {
                            return area(interpolator (t));
                        }
                    });


            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', messagesAreaResize);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', messagesAreaResize);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function messagesAreaResize() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width + margin.left + margin.right);

                // Width of appended group
                svg.attr("width", width + margin.left + margin.right);

                // Horizontal range
                x.range([0, width]);


                // Chart elements
                // -------------------------

                // Area path
                svg.selectAll('.d3-area').datum( data ).attr("d", area);
            }
        });
    }




    // Sparklines
    // ------------------------------

    // Initialize chart
    sparkline("#new-visitors", "line", 30, 35, "basis", 750, 2000, "#26A69A");
    sparkline("#new-sessions", "line", 30, 35, "basis", 750, 2000, "#FF7043");
    sparkline("#total-online", "line", 30, 35, "basis", 750, 2000, "#5C6BC0");
    sparkline("#server-load", "area", 30, 50, "basis", 750, 2000, "rgba(255,255,255,0.5)");

    // Chart setup
    function sparkline(element, chartType, qty, height, interpolation, duration, interval, color) {


        // Basic setup
        // ------------------------------

        // Define main variables
        var d3Container = d3.select(element),
            margin = {top: 0, right: 0, bottom: 0, left: 0},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom;


        // Generate random data (for demo only)
        var data = [];
        for (var i=0; i < qty; i++) {
            data.push(Math.floor(Math.random() * qty) + 5)
        }



        // Construct scales
        // ------------------------------

        // Horizontal
        var x = d3.scale.linear().range([0, width]);

        // Vertical
        var y = d3.scale.linear().range([height - 5, 5]);



        // Set input domains
        // ------------------------------

        // Horizontal
        x.domain([1, qty - 3])

        // Vertical
        y.domain([0, qty])
            


        // Construct chart layout
        // ------------------------------

        // Line
        var line = d3.svg.line()
            .interpolate(interpolation)
            .x(function(d, i) { return x(i); })
            .y(function(d, i) { return y(d); });

        // Area
        var area = d3.svg.area()
            .interpolate(interpolation)
            .x(function(d,i) { 
                return x(i); 
            })
            .y0(height)
            .y1(function(d) { 
                return y(d); 
            });



        // Create SVG
        // ------------------------------

        // Container
        var container = d3Container.append('svg');

        // SVG element
        var svg = container
            .attr('width', width + margin.left + margin.right)
            .attr('height', height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");



        // Add mask for animation
        // ------------------------------

        // Add clip path
        var clip = svg.append("defs")
            .append("clipPath")
            .attr('id', function(d, i) { return "load-clip-" + element.substring(1) })

        // Add clip shape
        var clips = clip.append("rect")
            .attr('class', 'load-clip')
            .attr("width", 0)
            .attr("height", height);

        // Animate mask
        clips
            .transition()
                .duration(1000)
                .ease('linear')
                .attr("width", width);



        //
        // Append chart elements
        //

        // Main path
        var path = svg.append("g")
            .attr("clip-path", function(d, i) { return "url(#load-clip-" + element.substring(1) + ")"})
            .append("path")
                .datum(data)
                .attr("transform", "translate(" + x(0) + ",0)");

        // Add path based on chart type
        if(chartType == "area") {
            path.attr("d", area).attr('class', 'd3-area').style("fill", color); // area
        }
        else {
            path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color); // line
        }

        // Animate path
        path
            .style('opacity', 0)
            .transition()
                .duration(750)
                .style('opacity', 1);



        // Set update interval. For demo only
        // ------------------------------

        setInterval(function() {

            // push a new data point onto the back
            data.push(Math.floor(Math.random() * qty) + 5);

            // pop the old data point off the front
            data.shift();

            update();

        }, interval);



        // Update random data. For demo only
        // ------------------------------

        function update() {

            // Redraw the path and slide it to the left
            path
                .attr("transform", null)
                .transition()
                    .duration(duration)
                    .ease("linear")
                    .attr("transform", "translate(" + x(0) + ",0)");

            // Update path type
            if(chartType == "area") {
                path.attr("d", area).attr('class', 'd3-area').style("fill", color)
            }
            else {
                path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color);
            }
        }



        // Resize chart
        // ------------------------------

        // Call function on window resize
        $(window).on('resize', resizeSparklines);

        // Call function on sidebar width change
        $(document).on('click', '.sidebar-control', resizeSparklines);

        // Resize function
        // 
        // Since D3 doesn't support SVG resize by default,
        // we need to manually specify parts of the graph that need to 
        // be updated on window resize
        function resizeSparklines() {

            // Layout variables
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


            // Layout
            // -------------------------

            // Main svg width
            container.attr("width", width + margin.left + margin.right);

            // Width of appended group
            svg.attr("width", width + margin.left + margin.right);

            // Horizontal range
            x.range([0, width]);


            // Chart elements
            // -------------------------

            // Clip mask
            clips.attr("width", width);

            // Line
            svg.select(".d3-line").attr("d", line);

            // Area
            svg.select(".d3-area").attr("d", area);
        }
    }




    // Daily revenue line chart
    // ------------------------------

    dailyRevenue('#today-revenue', 50); // initialize chart

    // Chart setup
    function dailyRevenue(element, height) {


        // Basic setup
        // ------------------------------

        // Add data set
        var dataset = [
            {
                "date": "04/13/14",
                "alpha": "60"
            }, {
                "date": "04/14/14",
                "alpha": "35"
            }, {
                "date": "04/15/14",
                "alpha": "65"
            }, {
                "date": "04/16/14",
                "alpha": "50"
            }, {
                "date": "04/17/14",
                "alpha": "65"
            }, {
                "date": "04/18/14",
                "alpha": "20"
            }, {
                "date": "04/19/14",
                "alpha": "60"
            }
        ];

        // Main variables
        var d3Container = d3.select(element),
            margin = {top: 0, right: 0, bottom: 0, left: 0},
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
            height = height - margin.top - margin.bottom,
            padding = 20;

        // Format date
        var parseDate = d3.time.format("%m/%d/%y").parse,
            formatDate = d3.time.format("%a, %B %e");



        // Add tooltip
        // ------------------------------

        var tooltip = d3.tip()
            .attr('class', 'd3-tip')
            .html(function (d) {
                return "<ul class='list-unstyled mb-5'>" +
                    "<li>" + "<div class='text-size-base mt-5 mb-5'><i class='icon-check2 position-left'></i>" + formatDate(d.date) + "</div>" + "</li>" +
                    "<li>" + "Sales: &nbsp;" + "<span class='text-semibold pull-right'>" + d.alpha + "</span>" + "</li>" +
                    "<li>" + "Revenue: &nbsp; " + "<span class='text-semibold pull-right'>" + "$" + (d.alpha * 25).toFixed(2) + "</span>" + "</li>" + 
                "</ul>";
            });



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append('svg');

        // Add SVG group
        var svg = container
                .attr('width', width + margin.left + margin.right)
                .attr('height', height + margin.top + margin.bottom)
                .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                    .call(tooltip);



        // Load data
        // ------------------------------

        dataset.forEach(function (d) {
            d.date = parseDate(d.date);
            d.alpha = +d.alpha;
        });



        // Construct scales
        // ------------------------------

        // Horizontal
        var x = d3.time.scale()
            .range([padding, width - padding]);

        // Vertical
        var y = d3.scale.linear()
            .range([height, 5]);



        // Set input domains
        // ------------------------------

        // Horizontal
        x.domain(d3.extent(dataset, function (d) {
            return d.date;
        }));

        // Vertical
        y.domain([0, d3.max(dataset, function (d) {
            return Math.max(d.alpha);
        })]);



        // Construct chart layout
        // ------------------------------

        // Line
        var line = d3.svg.line()
            .x(function(d) {
                return x(d.date);
            })
            .y(function(d) {
                return y(d.alpha)
            });



        //
        // Append chart elements
        //

        // Add mask for animation
        // ------------------------------

        // Add clip path
        var clip = svg.append("defs")
            .append("clipPath")
            .attr("id", "clip-line-small");

        // Add clip shape
        var clipRect = clip.append("rect")
            .attr('class', 'clip')
            .attr("width", 0)
            .attr("height", height);

        // Animate mask
        clipRect
              .transition()
                  .duration(1000)
                  .ease('linear')
                  .attr("width", width);



        // Line
        // ------------------------------

        // Path
        var path = svg.append('path')
            .attr({
                'd': line(dataset),
                "clip-path": "url(#clip-line-small)",
                'class': 'd3-line d3-line-medium'
            })
            .style('stroke', '#fff');

        // Animate path
        svg.select('.line-tickets')
            .transition()
                .duration(1000)
                .ease('linear');



        // Add vertical guide lines
        // ------------------------------

        // Bind data
        var guide = svg.append('g')
            .selectAll('.d3-line-guides-group')
            .data(dataset);

        // Append lines
        guide
            .enter()
            .append('line')
                .attr('class', 'd3-line-guides')
                .attr('x1', function (d, i) {
                    return x(d.date);
                })
                .attr('y1', function (d, i) {
                    return height;
                })
                .attr('x2', function (d, i) {
                    return x(d.date);
                })
                .attr('y2', function (d, i) {
                    return height;
                })
                .style('stroke', 'rgba(255,255,255,0.3)')
                .style('stroke-dasharray', '4,2')
                .style('shape-rendering', 'crispEdges');

        // Animate guide lines
        guide
            .transition()
                .duration(1000)
                .delay(function(d, i) { return i * 150; })
                .attr('y2', function (d, i) {
                    return y(d.alpha);
                });



        // Alpha app points
        // ------------------------------

        // Add points
        var points = svg.insert('g')
            .selectAll('.d3-line-circle')
            .data(dataset)
            .enter()
            .append('circle')
                .attr('class', 'd3-line-circle d3-line-circle-medium')
                .attr("cx", line.x())
                .attr("cy", line.y())
                .attr("r", 3)
                .style('stroke', '#fff')
                .style('fill', '#607D8B');



        // Animate points on page load
        points
            .style('opacity', 0)
            .transition()
                .duration(250)
                .ease('linear')
                .delay(1000)
                .style('opacity', 1);


        // Add user interaction
        points
            .on("mouseover", function (d) {
                tooltip.offset([-10, 0]).show(d);

                // Animate circle radius
                d3.select(this).transition().duration(250).attr('r', 4);
            })

            // Hide tooltip
            .on("mouseout", function (d) {
                tooltip.hide(d);

                // Animate circle radius
                d3.select(this).transition().duration(250).attr('r', 3);
            });

        // Change tooltip direction of first point
        d3.select(points[0][0])
            .on("mouseover", function (d) {
                tooltip.offset([0, 10]).direction('e').show(d);

                // Animate circle radius
                d3.select(this).transition().duration(250).attr('r', 4);
            })
            .on("mouseout", function (d) {
                tooltip.direction('n').hide(d);

                // Animate circle radius
                d3.select(this).transition().duration(250).attr('r', 3);
            });

        // Change tooltip direction of last point
        d3.select(points[0][points.size() - 1])
            .on("mouseover", function (d) {
                tooltip.offset([0, -10]).direction('w').show(d);

                // Animate circle radius
                d3.select(this).transition().duration(250).attr('r', 4);
            })
            .on("mouseout", function (d) {
                tooltip.direction('n').hide(d);

                // Animate circle radius
                d3.select(this).transition().duration(250).attr('r', 3);
            })



        // Resize chart
        // ------------------------------

        // Call function on window resize
        $(window).on('resize', revenueResize);

        // Call function on sidebar width change
        $(document).on('click', '.sidebar-control', revenueResize);

        // Resize function
        // 
        // Since D3 doesn't support SVG resize by default,
        // we need to manually specify parts of the graph that need to 
        // be updated on window resize
        function revenueResize() {

            // Layout variables
            width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


            // Layout
            // -------------------------

            // Main svg width
            container.attr("width", width + margin.left + margin.right);

            // Width of appended group
            svg.attr("width", width + margin.left + margin.right);

            // Horizontal range
            x.range([padding, width - padding]);


            // Chart elements
            // -------------------------

            // Mask
            clipRect.attr("width", width);

            // Line path
            svg.selectAll('.d3-line').attr("d", line(dataset));

            // Circles
            svg.selectAll('.d3-line-circle').attr("cx", line.x());

            // Guide lines
            svg.selectAll('.d3-line-guides')
                .attr('x1', function (d, i) {
                    return x(d.date);
                })
                .attr('x2', function (d, i) {
                    return x(d.date);
                });
        }
    }




    // Marketing campaigns progress pie chart
    // ------------------------------

    // Initialize chart
    progressMeter("#today-progress", 20, 20, '#7986CB');
    progressMeter("#yesterday-progress", 20, 20, '#7986CB');

    // Chart setup
    function progressMeter(element, width, height, color) {


        // Basic setup
        // ------------------------------

        // Main variables
        var d3Container = d3.select(element),
            border = 2,
            radius = Math.min(width / 2, height / 2) - border,
            twoPi = 2 * Math.PI,
            progress = $(element).data('progress'),
            total = 100;



        // Construct chart layout
        // ------------------------------

        // Arc
        var arc = d3.svg.arc()
            .startAngle(0)
            .innerRadius(0)
            .outerRadius(radius)
            .endAngle(function(d) {
              return (d.value / d.size) * 2 * Math.PI; 
            })



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg");

        // Add SVG group
        var svg = container
            .attr("width", width)
            .attr("height", height)
            .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");



        //
        // Append chart elements
        //

        // Progress group
        var meter = svg.append("g")
            .attr("class", "progress-meter");

        // Background
        meter.append("path")
            .attr("d", arc.endAngle(twoPi))
            .style('fill', '#fff')
            .style('stroke', color)
            .style('stroke-width', 1.5);

        // Foreground
        var foreground = meter.append("path")
            .style('fill', color);

        // Animate foreground path
        foreground
            .transition()
                .ease("cubic-out")
                .duration(2500)
                .attrTween("d", arcTween);


        // Tween arcs
        function arcTween() {
            var i = d3.interpolate(0, progress);
            return function(t) {
                var currentProgress = progress / (100/t);
                var endAngle = arc.endAngle(twoPi * (currentProgress));
                return arc(i(endAngle));
            };
        }
    }




    // Marketing campaigns donut chart
    // ------------------------------

    // Initialize chart
    campaignDonut("#campaigns-donut", 42);

    // Chart setup
    function campaignDonut(element, size) {


        // Basic setup
        // ------------------------------

        // Add data set
        var data = [
            {
                "browser": "Google Adwords",
                "icon": "<i class='icon-google position-left'></i>",
                "value": 1047,
                "color" : "#66BB6A"
            }, {
                "browser": "Social media",
                "icon": "<i class='icon-share4 position-left'></i>",
                "value": 2948,
                "color": "#9575CD"
            }, {
                "browser":"Youtube video",
                "icon": "<i class='icon-youtube position-left'></i>",
                "value": 3909,
                "color": "#FF7043"
            }
        ];

        // Main variables
        var d3Container = d3.select(element),
            distance = 2, // reserve 2px space for mouseover arc moving
            radius = (size/2) - distance,
            sum = d3.sum(data, function(d) { return d.value; })



        // Tooltip
        // ------------------------------

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .direction('e')
            .html(function (d) {
                return "<ul class='list-unstyled mb-5'>" +
                    "<li>" + "<div class='text-size-base mb-5 mt-5'>" + d.data.icon + d.data.browser + "</div>" + "</li>" +
                    "<li>" + "Visits: &nbsp;" + "<span class='text-semibold pull-right'>" + d.value + "</span>" + "</li>" +
                    "<li>" + "Share: &nbsp;" + "<span class='text-semibold pull-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                "</ul>";
            })



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg").call(tip);
        
        // Add SVG group
        var svg = container
            .attr("width", size)
            .attr("height", size)
            .append("g")
                .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  



        // Construct chart layout
        // ------------------------------

        // Pie
        var pie = d3.layout.pie()
            .sort(null)
            .startAngle(Math.PI)
            .endAngle(3 * Math.PI)
            .value(function (d) { 
                return d.value;
            }); 

        // Arc
        var arc = d3.svg.arc()
            .outerRadius(radius)
            .innerRadius(radius / 2);



        //
        // Append chart elements
        //

        // Group chart elements
        var arcGroup = svg.selectAll(".d3-arc")
            .data(pie(data))
            .enter()
            .append("g") 
                .attr("class", "d3-arc")
                .style('stroke', '#fff')
                .style('cursor', 'pointer');
        
        // Append path
        var arcPath = arcGroup
            .append("path")
            .style("fill", function (d) { return d.data.color; });

        // Add tooltip
        arcPath
            .on('mouseover', function (d, i) {

                // Transition on mouseover
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('elastic')
                    .attr('transform', function (d) {
                        d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                        var x = Math.sin(d.midAngle) * distance;
                        var y = -Math.cos(d.midAngle) * distance;
                        return 'translate(' + x + ',' + y + ')';
                    });
            })

            .on("mousemove", function (d) {
                
                // Show tooltip on mousemove
                tip.show(d)
                    .style("top", (d3.event.pageY - 40) + "px")
                    .style("left", (d3.event.pageX + 30) + "px");
            })

            .on('mouseout', function (d, i) {

                // Mouseout transition
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('bounce')
                    .attr('transform', 'translate(0,0)');

                // Hide tooltip
                tip.hide(d);
            });

        // Animate chart on load
        arcPath
            .transition()
                .delay(function(d, i) { return i * 500; })
                .duration(500)
                .attrTween("d", function(d) {
                    var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);  
                    }; 
                });
    }




    // Campaign status donut chart
    // ------------------------------

    // Initialize chart
    campaignStatusPie("#campaign-status-pie", 42);

    // Chart setup
    function campaignStatusPie(element, size) {


        // Basic setup
        // ------------------------------

        // Add data set
        var data = [
            {
                "status": "Active campaigns",
                "icon": "<span class='status-mark border-blue-300 position-left'></span>",
                "value": 439,
                "color": "#29B6F6"
            }, {
                "status": "Closed campaigns",
                "icon": "<span class='status-mark border-danger-300 position-left'></span>",
                "value": 290,
                "color": "#EF5350"
            }, {
                "status": "Pending campaigns",
                "icon": "<span class='status-mark border-success-300 position-left'></span>",
                "value": 190,
                "color": "#81C784"
            }, {
                "status": "Campaigns on hold",
                "icon": "<span class='status-mark border-grey-300 position-left'></span>",
                "value": 148,
                "color": "#999"
            }
        ];

        // Main variables
        var d3Container = d3.select(element),
            distance = 2, // reserve 2px space for mouseover arc moving
            radius = (size/2) - distance,
            sum = d3.sum(data, function(d) { return d.value; })



        // Tooltip
        // ------------------------------

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .direction('e')
            .html(function (d) {
                return "<ul class='list-unstyled mb-5'>" +
                    "<li>" + "<div class='text-size-base mb-5 mt-5'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                    "<li>" + "Total: &nbsp;" + "<span class='text-semibold pull-right'>" + d.value + "</span>" + "</li>" +
                    "<li>" + "Share: &nbsp;" + "<span class='text-semibold pull-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                "</ul>";
            })



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg").call(tip);
        
        // Add SVG group
        var svg = container
            .attr("width", size)
            .attr("height", size)
            .append("g")
                .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  



        // Construct chart layout
        // ------------------------------

        // Pie
        var pie = d3.layout.pie()
            .sort(null)
            .startAngle(Math.PI)
            .endAngle(3 * Math.PI)
            .value(function (d) { 
                return d.value;
            }); 

        // Arc
        var arc = d3.svg.arc()
            .outerRadius(radius)
            .innerRadius(radius / 2);



        //
        // Append chart elements
        //

        // Group chart elements
        var arcGroup = svg.selectAll(".d3-arc")
            .data(pie(data))
            .enter()
            .append("g") 
                .attr("class", "d3-arc")
                .style('stroke', '#fff')
                .style('cursor', 'pointer');
        
        // Append path
        var arcPath = arcGroup
            .append("path")
            .style("fill", function (d) { return d.data.color; });

        // Add tooltip
        arcPath
            .on('mouseover', function (d, i) {

                // Transition on mouseover
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('elastic')
                    .attr('transform', function (d) {
                        d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                        var x = Math.sin(d.midAngle) * distance;
                        var y = -Math.cos(d.midAngle) * distance;
                        return 'translate(' + x + ',' + y + ')';
                    });
            })

            .on("mousemove", function (d) {
                
                // Show tooltip on mousemove
                tip.show(d)
                    .style("top", (d3.event.pageY - 40) + "px")
                    .style("left", (d3.event.pageX + 30) + "px");
            })

            .on('mouseout', function (d, i) {

                // Mouseout transition
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('bounce')
                    .attr('transform', 'translate(0,0)');

                // Hide tooltip
                tip.hide(d);
            });

        // Animate chart on load
        arcPath
            .transition()
                .delay(function(d, i) { return i * 500; })
                .duration(500)
                .attrTween("d", function(d) {
                    var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);  
                    }; 
                });
    }




    // Tickets status donut chart
    // ------------------------------

    // Initialize chart
    ticketStatusDonut("#tickets-status", 42);

    // Chart setup
    function ticketStatusDonut(element, size) {


        // Basic setup
        // ------------------------------

        // Add data set
        var data = [
            {
                "status": "Pending tickets",
                "icon": "<i class='status-mark border-blue-300 position-left'></i>",
                "value": 295,
                "color": "#29B6F6"
            }, {
                "status": "Resolved tickets",
                "icon": "<i class='status-mark border-success-300 position-left'></i>",
                "value": 189,
                "color": "#66BB6A"
            }, {
                "status": "Closed tickets",
                "icon": "<i class='status-mark border-danger-300 position-left'></i>",
                "value": 277,
                "color": "#EF5350"
            }
        ];

        // Main variables
        var d3Container = d3.select(element),
            distance = 2, // reserve 2px space for mouseover arc moving
            radius = (size/2) - distance,
            sum = d3.sum(data, function(d) { return d.value; })



        // Tooltip
        // ------------------------------

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .direction('e')
            .html(function (d) {
                return "<ul class='list-unstyled mb-5'>" +
                    "<li>" + "<div class='text-size-base mb-5 mt-5'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                    "<li>" + "Total: &nbsp;" + "<span class='text-semibold pull-right'>" + d.value + "</span>" + "</li>" +
                    "<li>" + "Share: &nbsp;" + "<span class='text-semibold pull-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                "</ul>";
            })



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg").call(tip);
        
        // Add SVG group
        var svg = container
            .attr("width", size)
            .attr("height", size)
            .append("g")
                .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  



        // Construct chart layout
        // ------------------------------

        // Pie
        var pie = d3.layout.pie()
            .sort(null)
            .startAngle(Math.PI)
            .endAngle(3 * Math.PI)
            .value(function (d) { 
                return d.value;
            }); 

        // Arc
        var arc = d3.svg.arc()
            .outerRadius(radius)
            .innerRadius(radius / 2);



        //
        // Append chart elements
        //

        // Group chart elements
        var arcGroup = svg.selectAll(".d3-arc")
            .data(pie(data))
            .enter()
            .append("g") 
                .attr("class", "d3-arc")
                .style('stroke', '#fff')
                .style('cursor', 'pointer');
        
        // Append path
        var arcPath = arcGroup
            .append("path")
            .style("fill", function (d) { return d.data.color; });

        // Add tooltip
        arcPath
            .on('mouseover', function (d, i) {

                // Transition on mouseover
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('elastic')
                    .attr('transform', function (d) {
                        d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                        var x = Math.sin(d.midAngle) * distance;
                        var y = -Math.cos(d.midAngle) * distance;
                        return 'translate(' + x + ',' + y + ')';
                    });
            })

            .on("mousemove", function (d) {
                
                // Show tooltip on mousemove
                tip.show(d)
                    .style("top", (d3.event.pageY - 40) + "px")
                    .style("left", (d3.event.pageX + 30) + "px");
            })

            .on('mouseout', function (d, i) {

                // Mouseout transition
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('bounce')
                    .attr('transform', 'translate(0,0)');

                // Hide tooltip
                tip.hide(d);
            });

        // Animate chart on load
        arcPath
            .transition()
                .delay(function(d, i) { return i * 500; })
                .duration(500)
                .attrTween("d", function(d) {
                    var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);  
                    }; 
                });
    }




    // Bar charts with random data
    // ------------------------------

    // Initialize charts
    generateBarChart("#hours-available-bars", 24, 40, true, "elastic", 1200, 50, "#EC407A", "hours");
    generateBarChart("#goal-bars", 24, 40, true, "elastic", 1200, 50, "#5C6BC0", "goal");
    generateBarChart("#members-online", 24, 50, true, "elastic", 1200, 50, "rgba(255,255,255,0.5)", "members");

    // Chart setup
    function generateBarChart(element, barQty, height, animate, easing, duration, delay, color, tooltip) {


        // Basic setup
        // ------------------------------

        // Add data set
        var bardata = [];
        for (var i=0; i < barQty; i++) {
            bardata.push(Math.round(Math.random()*10) + 10)
        }

        // Main variables
        var d3Container = d3.select(element),
            width = d3Container.node().getBoundingClientRect().width;
        


        // Construct scales
        // ------------------------------

        // Horizontal
        var x = d3.scale.ordinal()
            .rangeBands([0, width], 0.3)

        // Vertical
        var y = d3.scale.linear()
            .range([0, height]);



        // Set input domains
        // ------------------------------

        // Horizontal
        x.domain(d3.range(0, bardata.length))

        // Vertical
        y.domain([0, d3.max(bardata)])



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append('svg');

        // Add SVG group
        var svg = container
            .attr('width', width)
            .attr('height', height)
            .append('g');



        //
        // Append chart elements
        //

        // Bars
        var bars = svg.selectAll('rect')
            .data(bardata)
            .enter()
            .append('rect')
                .attr('class', 'd3-random-bars')
                .attr('width', x.rangeBand())
                .attr('x', function(d,i) {
                    return x(i);
                })
                .style('fill', color);



        // Tooltip
        // ------------------------------

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0]);

        // Show and hide
        if(tooltip == "hours" || tooltip == "goal" || tooltip == "members") {
            bars.call(tip)
                .on('mouseover', tip.show)
                .on('mouseout', tip.hide);
        }

        // Daily meetings tooltip content
        if(tooltip == "hours") {
            tip.html(function (d, i) {
                return "<div class='text-center'>" +
                        "<h6 class='no-margin'>" + d + "</h6>" +
                        "<span class='text-size-small'>meetings</span>" +
                        "<div class='text-size-small'>" + i + ":00" + "</div>" +
                    "</div>"
            });
        }

        // Statements tooltip content
        if(tooltip == "goal") {
            tip.html(function (d, i) {
                return "<div class='text-center'>" +
                        "<h6 class='no-margin'>" + d + "</h6>" +
                        "<span class='text-size-small'>statements</span>" +
                        "<div class='text-size-small'>" + i + ":00" + "</div>" +
                    "</div>"
            });
        }

        // Online members tooltip content
        if(tooltip == "members") {
            tip.html(function (d, i) {
                return "<div class='text-center'>" +
                        "<h6 class='no-margin'>" + d + "0" + "</h6>" +
                        "<span class='text-size-small'>members</span>" +
                        "<div class='text-size-small'>" + i + ":00" + "</div>" +
                    "</div>"
            });
        }



        // Bar loading animation
        // ------------------------------

        // Choose between animated or static
        if(animate) {
            withAnimation();
        } else {
            withoutAnimation();
        }

        // Animate on load
        function withAnimation() {
            bars
                .attr('height', 0)
                .attr('y', height)
                .transition()
                    .attr('height', function(d) {
                        return y(d);
                    })
                    .attr('y', function(d) {
                        return height - y(d);
                    })
                    .delay(function(d, i) {
                        return i * delay;
                    })
                    .duration(duration)
                    .ease(easing);
        }

        // Load without animateion
        function withoutAnimation() {
            bars
                .attr('height', function(d) {
                    return y(d);
                })
                .attr('y', function(d) {
                    return height - y(d);
                })
        }



        // Resize chart
        // ------------------------------

        // Call function on window resize
        $(window).on('resize', barsResize);

        // Call function on sidebar width change
        $(document).on('click', '.sidebar-control', barsResize);

        // Resize function
        // 
        // Since D3 doesn't support SVG resize by default,
        // we need to manually specify parts of the graph that need to 
        // be updated on window resize
        function barsResize() {

            // Layout variables
            width = d3Container.node().getBoundingClientRect().width;


            // Layout
            // -------------------------

            // Main svg width
            container.attr("width", width);

            // Width of appended group
            svg.attr("width", width);

            // Horizontal range
            x.rangeBands([0, width], 0.3);


            // Chart elements
            // -------------------------

            // Bars
            svg.selectAll('.d3-random-bars')
                .attr('width', x.rangeBand())
                .attr('x', function(d,i) {
                    return x(i);
                });
        }
    }




    // Animated progress chart
    // ------------------------------

    // Initialize charts
    progressCounter('#hours-available-progress', 38, 2, "#F06292", 0.68, "icon-watch text-pink-400", 'Hours available', '64% average')
    progressCounter('#goal-progress', 38, 2, "#5C6BC0", 0.82, "icon-trophy3 text-indigo-400", 'Productivity goal', '87% average')

    // Chart setup
    function progressCounter(element, radius, border, color, end, iconClass, textTitle, textAverage) {


        // Basic setup
        // ------------------------------

        // Main variables
        var d3Container = d3.select(element),
            startPercent = 0,
            iconSize = 32,
            endPercent = end,
            twoPi = Math.PI * 2,
            formatPercent = d3.format('.0%'),
            boxSize = radius * 2;

        // Values count
        var count = Math.abs((endPercent - startPercent) / 0.01);

        // Values step
        var step = endPercent < startPercent ? -0.01 : 0.01;



        // Create chart
        // ------------------------------

        // Add SVG element
        var container = d3Container.append('svg');

        // Add SVG group
        var svg = container
            .attr('width', boxSize)
            .attr('height', boxSize)
            .append('g')
                .attr('transform', 'translate(' + (boxSize / 2) + ',' + (boxSize / 2) + ')');



        // Construct chart layout
        // ------------------------------

        // Arc
        var arc = d3.svg.arc()
            .startAngle(0)
            .innerRadius(radius)
            .outerRadius(radius - border);



        //
        // Append chart elements
        //

        // Paths
        // ------------------------------

        // Background path
        svg.append('path')
            .attr('class', 'd3-progress-background')
            .attr('d', arc.endAngle(twoPi))
            .style('fill', '#eee');

        // Foreground path
        var foreground = svg.append('path')
            .attr('class', 'd3-progress-foreground')
            .attr('filter', 'url(#blur)')
            .style('fill', color)
            .style('stroke', color);

        // Front path
        var front = svg.append('path')
            .attr('class', 'd3-progress-front')
            .style('fill', color)
            .style('fill-opacity', 1);



        // Text
        // ------------------------------

        // Percentage text value
        var numberText = d3.select(element)
            .append('h2')
                .attr('class', 'mt-15 mb-5')

        // Icon
        d3.select(element)
            .append("i")
                .attr("class", iconClass + " counter-icon")
                .attr('style', 'top: ' + ((boxSize - iconSize) / 2) + 'px');

        // Title
        d3.select(element)
            .append('div')
                .text(textTitle);

        // Subtitle
        d3.select(element)
            .append('div')
                .attr('class', 'text-size-small text-muted')
                .text(textAverage);



        // Animation
        // ------------------------------

        // Animate path
        function updateProgress(progress) {
            foreground.attr('d', arc.endAngle(twoPi * progress));
            front.attr('d', arc.endAngle(twoPi * progress));
            numberText.text(formatPercent(progress));
        }

        // Animate text
        var progress = startPercent;
        (function loops() {
            updateProgress(progress);
            if (count > 0) {
                count--;
                progress += step;
                setTimeout(loops, 10);
            }
        })();
    }
    
    // Other codes
    // ------------------------------

    // Grab first letter and insert to the icon
    $(".table tr").each(function (i) {

        // Title
        var $title = $(this).find('.letter-icon-title'),
            letter = $title.eq(0).text().charAt(0).toUpperCase();

        // Icon
        var $icon = $(this).find('.letter-icon');
            $icon.eq(0).text(letter);
    });

    // Ripple effect
	$(".btn:not(.disabled):not(.multiselect.btn-default):not(.bootstrap-select .btn-default), .navigation li:not(.disabled) a, .nav > li:not(.disabled) > a, .sidebar-user-material-menu > a, .sidebar-user-material-content > a, .select2-selection--single[class*=bg-], .breadcrumb-elements > li:not(.disabled) > a, .wizard > .actions a, .ui-button:not(.ui-dialog-titlebar-close), .ui-tabs-anchor:not(.ui-state-disabled), .plupload_button:not(.plupload_disabled), .fc-button, .pagination > li:not(.disabled) > a, .pagination > li:not(.disabled) > span, .pager > li:not(.disabled) > a, .pager > li:not(.disabled) > span").ripple({
		dragging: false,
		adaptPos: false,
		scaleMode: false
	});

	// Unbind ripple in Datepaginator
	$('.dp-item, .dp-nav, .sidebar-xs .sidebar-main .navigation > li > a').ripple({unbind: true});
	
	$(document).on('click', '.sidebar-control', function() {
		if($('body').hasClass('sidebar-xs')) {
			$('.sidebar-main .navigation > li > a').ripple({unbind: true});
		}
		else {
			$('.sidebar-main .navigation > li > a').ripple({unbind: false});
		}
	});

    // Checkboxes/radios in addons
    // ------------------------------

    // Switchery
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }


    // Styled checkboxes/radios
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });

    // Update uniform when select between styled and unstyled
    $('.input-group-addon input[type=radio]').on('click', function() {
        $.uniform.update("[name=addon-radio]");
    });



    // Touchspin spinners
    // ------------------------------

    // Basic example
    $(".touchspin-basic").TouchSpin({
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });


    // Postfix
    $(".touchspin-postfix").TouchSpin({
        min: 0,
        max: 100,
        step: 0.1,
        decimals: 2,
        postfix: '%'
    });


    // Prefix
    $(".touchspin-prefix").TouchSpin({
        min: 0,
        max: 100,
        step: 0.1,
        decimals: 2,
        prefix: '$'
    });


    // Init with empty values
    $(".touchspin-empty").TouchSpin();


    // Disable mousewheel
    $(".touchspin-no-mousewheel").TouchSpin({
        mousewheel: false
    });


    // Incremental/decremental steps
    $(".touchspin-step").TouchSpin({
        step: 10
    });


    // Set value
    $(".touchspin-set-value").TouchSpin({
        initval: 40
    });


    // Inside button group
    $(".touchspin-button-group").TouchSpin({
        prefix: "pre",
        postfix: "post"
    });


    // Vertical spinners
    $(".touchspin-vertical").TouchSpin({
        verticalbuttons: true,
        verticalupclass: 'icon-arrow-up22',
        verticaldownclass: 'icon-arrow-down22'
    });



    // Touchspin colors
    // ------------------------------

    //
    // Addons
    //

    // Default
    $(".touchspin-addon-default").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });

    // Primary
    $(".touchspin-addon-primary").TouchSpin({
        prefix_extraclass: 'input-group-addon-primary',
        postfix_extraclass: 'input-group-addon-primary',
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });

    // Danger
    $(".touchspin-addon-danger").TouchSpin({
        prefix_extraclass: 'input-group-addon-danger',
        postfix_extraclass: 'input-group-addon-danger',
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });

    // Success
    $(".touchspin-addon-success").TouchSpin({
        prefix_extraclass: 'input-group-addon-success',
        postfix_extraclass: 'input-group-addon-success',
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });

    // Warning
    $(".touchspin-addon-warning").TouchSpin({
        prefix_extraclass: 'input-group-addon-warning',
        postfix_extraclass: 'input-group-addon-warning',
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });

    // Info
    $(".touchspin-addon-info").TouchSpin({
        prefix_extraclass: 'input-group-addon-info',
        postfix_extraclass: 'input-group-addon-info',
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>'
    });


    //
    // Buttons
    //

    // Default
    $(".touchspin-button-default").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>',
        buttondown_class: "btn btn-default",
        buttonup_class: "btn btn-default"
    });

    // Primary
    $(".touchspin-button-primary").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>',
        buttondown_class: "btn btn-primary",
        buttonup_class: "btn btn-primary"
    });

    // Danger
    $(".touchspin-button-danger").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>',
        buttondown_class: "btn btn-danger",
        buttonup_class: "btn btn-danger"
    });

    // Success
    $(".touchspin-button-success").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>',
        buttondown_class: "btn btn-success",
        buttonup_class: "btn btn-success"
    });

    // Warning
    $(".touchspin-button-warning").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>',
        buttondown_class: "btn btn-warning",
        buttonup_class: "btn btn-warning"
    });

    // Info
    $(".touchspin-button-info").TouchSpin({
        prefix: '<i class="icon-accessibility"></i>',
        postfix: '<i class="icon-paragraph-justify2"></i>',
        buttondown_class: "btn btn-info",
        buttonup_class: "btn btn-info"
    });

    // Style checkboxes and radios
    $('.styled').uniform();

    $('select').select2({
        minimumResultsForSearch: Infinity
    });

});

jQuery(document).ready(function($) {

  /*
    if ($('#queueorgroup').length > 0) {
        $('#queueorgroup').on('change', function() {
            if (this.value == 'groups') {
                $('#queueselection').hide();
                $('#groupselection').show();
                $('#queueselectionavailable').hide();
                $('#groupselectionavailable').show();
            } else {
                $('#queueselection').show();
                $('#groupselection').hide();
                $('#queueselectionavailable').show();
                $('#groupselectionavailable').hide();
            }

            var urlin = 'set-sesvar.php';
            if (this.value == 'groups') {
                var pars = 'sesvar=grouporqueueshowing&value=groups';
            } else {
                var pars = 'sesvar=grouporqueueshowing&value=queues';
            }
            $.ajax({
                url: urlin,
                type: 'POST',
                data: pars
            });

        });
    }

    $('#loader').hide();

    if ($('#searchsetup')) {
        $('#searchsetup').keydown(function(event) {
            if (event.key == 'enter') {;
                var page = getQueryParameter('page');
                var filter = $('searchsetup').value;
                var myURI = new URI();
                var pars = "page=" + page + "&filter=" + filter;
                myURI.set('query', pars);
                myURI.go();
                return false;
            }
        });
    }

    if ($('#myform_List_Queue_from')) {

        $('#myform_List_Queue_from').dblclick(function() {
            List_move_around('right', false, 'queues');
        });

        $('#myform_List_Queue_to').dblclick(function() {
            List_move_around('left', false, 'queues');
        });

        $('#myform_List_Group_from').dblclick(function() {
            List_move_around('right', false, 'queues');
        });

        $('#myform_List_Group_to').dblclick(function() {
            List_move_around('left', false, 'queues');
        });

        $('#myform_List_Agent_from').dblclick(function() {
            List_move_around('right', false, 'agents');
        });

        $('#myform_List_Agent_to').dblclick(function() {
            List_move_around('left', false, 'agents');
        });

        $('#showReport').click(function() {
            envia();
            return false;
        });

        $('#month1').change(function() {
            dateChange('day1', 'month1', 'year1');
        });

        $('#year1').change(function() {
            checkMore($('#year1')[0], $('#year1_start_year').val(), $('#year1_end_year').val(), $('#year1_super_start_year').val(), $('#year1_super_end_year').val());
            dateChange('day1', 'month1', 'year1');
        });

        $('#month2').change(function() {
            dateChange('day2', 'month2', 'year2');
        });

        $('#year2').change(function() {
            checkMore($('#year2')[0], $('#year2_start_year').val(), $('#year2_end_year').val(), $('#year2_super_start_year').val(), $('#year2_super_end_year').val());
            dateChange('day2', 'month2', 'year2');
        });

    }

    /*    if($('.resizeDiv').length > 0 ) {
    $('.resizeDiv').resizable({ 
        start: function(e, ui) {
            $(this).addClass("unselectable");
        },
        stop: function(e, ui) {
            $(this).removeClass("unselectable");
        }
    });
    }
*/
    resizeCharts();

    $('.toggler').click(function(index, el) {
        $(this).next().slideToggle();
    });


    init2();

    wavesurfer = WaveSurfer.create({
        container: '#waveform',
        waveColor: 'violet',
        progressColor: 'purple'
    });

    var formatTime = function(time) {
        return [
            Math.floor((time % 3600) / 60), // minutes
            ('00' + Math.floor(time % 60)).slice(-2) // seconds
        ].join(':');
    };

    // Show current time
    wavesurfer.on('audioprocess', function() {
        if ($('#waveform_counter').length > 0) {
            $('#waveform_counter').text(formatTime(wavesurfer.getCurrentTime()));
        }
    });

    // Show clip duration
    wavesurfer.on('ready', function() {
        if ($('#waveform_duration').length > 0) {
            $('#waveform_duration').text(formatTime(wavesurfer.getDuration()));
        }
    });



    var slider = document.querySelector('#slider');

    slider.oninput = function() {
        var zoomLevel = Number(slider.value);
        wavesurfer.zoom(zoomLevel);
    };


    var offset = 300,
        //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
        //duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
        //grab the "back to top" link
        $back_to_top = $('.cd-top');

    //hide or show the "back to top" link
    $(window).scroll(function() {
        ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('cd-fade-out');
        }
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function() {
            $back_to_top.removeClass('cd-is-visible cd-fade-out');
        }, 5000));
    });

    //smooth scroll to top
    $back_to_top.on('click', function(event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
        }, scroll_top_duration);
    });

});

function initDataTable() {

    dtable = new Object();

    $('table.dattab').each(function() {

        tabid = $(this).attr('id');

        reportName = $('#' + tabid).find('caption').text().trim();

        if ($(this).find("tbody tr").length > 0) {

            orientation = 'portrait';
            if ($(this).find("tbody tr:first td").length > 7) {
                orientation = 'landscape';
            }

            dtable[tabid] = $(this).DataTable({

                bInfo: false,
                mark: true,
                stateSave: true,
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class=\"fa fa-file-excel-o\"></i>',
                    titleAttr: 'Excel',
                    title: reportName,
                    className: 'helpleft'
                }, {
                    extend: 'csvHtml5',
                    text: '<i class=\"fa fa-file-text-o\"></i>',
                    titleAttr: 'CSV',
                    title: reportName,
                    className: 'helpleft'
                }, {
                    extend: 'pdfHtml5',
                    text: '<i class=\"fa fa-file-pdf-o\"></i>',
                    orientation: orientation,
                    titleAttr: 'PDF',
                    title: reportName,
                    className: 'helpleft'
                }, {
                    extend: 'colvis',
                    text: '<i class=\"fa fa-eye\"></i>',
                    className: 'helpleft',
                    titleAttr: idiom['jscolumnvisibility']
                }],
                dom: "<'row'<'col-sm-6'Bl><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                language: datatablelanguage
            });
        }

    });

    if (typeof postInitTable == 'function') {
        postInitTable()
    }
    $('.helpleft').tipTip({
        defaultPosition: 'left'
    });
    $('.helpright').tipTip({
        defaultPosition: 'right'
    });
    $('.helptop').tipTip({
        defaultPosition: 'top'
    });


}

function resizeCharts() {
    $('.resizeDiv').each(function(index) {
        var ancho = $(this).width();
        var alto = Math.floor(ancho / 1.6);
        $(this).height(alto);
        var elechart = $(this).children(':first-child').attr('id');
        $(this).children(':first-child').empty();
        var plotchart = "plot" + elechart;
        $('#' + elechart).height(alto * 0.93);
        try {
            eval(plotchart + ".replot({});");
        } catch (error) {
            debug('no chart ' + error);
        }
    });
}

$(window).resize(function() {
    resizeCharts();
});


function getQueryParameter(param) {
    var myreturn = "";
    var strHref = window.location.href;
    if (strHref.indexOf("?") > -1) {
        pos = strHref.indexOf("?") + 1;
        var strQueryString = strHref.substr(pos).toLowerCase();
        var queryObj = strQueryString.parseQueryString();
        myreturn = queryObj[param];
    };
    if (myreturn === undefined) {
        myreturn = "";
    };
    return myreturn;
};

function init2() {
    // Function called from jslanguage.php

    if ($('#day1').length > 0) {
        // Set and validate dates on load
        dateChange('day1', 'month1', 'year1');
        dateChange('day2', 'month2', 'year2');
    };

    if (error_type != "") {
        popalert(error_type, error_text);
    };

    //initDataTable();

    $('.helpleft').tipTip({
        defaultPosition: 'left'
    });
    $('.helpright').tipTip({
        defaultPosition: 'right'
    });
    $('.helptop').tipTip({
        defaultPosition: 'top'
    });

}

function List_move_around(direction, all, box) {
    if (direction == "right") {
        if (box == "queues") {
            if ($('#queueselectionavailable').is(':visible')) {
                box1 = "List_Queue_available";
                box2 = "List_Queue[]";
            } else {
                box1 = "List_Group_available";
                box2 = "List_Group[]";
            }
        } else {
            box1 = "List_Agent_available";
            box2 = "List_Agent[]";
        }
    } else {
        if (box == "queues") {
            if ($('#queueselectionavailable').is(':visible')) {
                box1 = "List_Queue[]";
                box2 = "List_Queue_available" + "";
            } else {
                box1 = "List_Group[]";
                box2 = "List_Group_available" + "";
            }
        } else {
            box1 = "List_Agent[]";
            box2 = "List_Agent_available" + "";
        }
    }


    for (var i = 0; i < document.forms[0].elements[box1].length; i++) {
        if ((document.forms[0].elements[box1][i].selected || all)) {
            document.forms[0].elements[box2].options[document.forms[0].elements[box2].length] = new Option(document.forms[0].elements[box1].options[i].text, document.forms[0].elements[box1][i].value);
            document.forms[0].elements[box1][i] = null;
            i--;
        }
    }
    return false;
}

function List_Queue_check_submit() {
    if ($('#queueselectionavailable').is(':visible')) {
        box = "List_Queue[]";
        if (document.forms[0].elements[box]) {
            for (var i = 0; i < document.forms[0].elements[box].length; i++) {
                document.forms[0].elements[box][i].selected = true;
            }
        }
    } else {
        box = "List_Group[]";
        if (document.forms[0].elements[box]) {
            for (var i = 0; i < document.forms[0].elements[box].length; i++) {
                document.forms[0].elements[box][i].selected = true;
            }
        }
    }
    box = "List_Agent[]";
    if (document.forms[0].elements[box]) {
        for (var i = 0; i < document.forms[0].elements[box].length; i++) {
            document.forms[0].elements[box][i].selected = true;
        }
    }
    return true;
}

function envia() {

    List_Queue_check_submit();

    if ($('#queueselectionavailable').is(':visible')) {
        box = "List_Queue[]";
        if (document.forms[0].elements[box].length == 0) {
            popalert("warning", "You must select at least one queue");
            return false;
        }
    } else {
        box = "List_Group[]";
        if (document.forms[0].elements[box].length == 0) {
            popalert("warning", "You must select at least one group");
            return false;
        }
    }

    box = "List_Agent[]";
    if (document.forms[0].elements[box].length == 0) {
        popalert("warning", "You must select at least one agent");
        return false;
    }

    // Set start and end date strings from mysql and for js check
    year_s = $('#year1').val();
    month_s = parseInt($('#month1').val()) + 1;
    day_s = $('#day1').val();
    if (String(month_s).length == 1) {
        month_s = "0" + month_s
    }
    if (String(day_s).length == 1) {
        day_s = "0" + day_s
    }
    fecha_s = year_s + "-" + month_s + "-" + day_s + " 00:00:00";
    fecha_check_s = year_s + month_s + day_s;

    year_e = $('#year2').val();
    month_e = parseInt($('#month2').val()) + 1;
    day_e = $('#day2').val();
    if (String(month_e).length == 1) {
        month_e = "0" + month_e
    }
    if (String(day_e).length == 1) {
        day_e = "0" + day_e
    }
    fecha_e = year_e + "-" + month_e + "-" + day_e + " 23:59:59";
    fecha_check_e = year_e + month_e + day_e;

    $('#start').val(fecha_s);
    $('#end').val(fecha_e);

    // Set hour range for js check and compute seconds for mysql
    hour1 = $('#hour1').val();
    hour2 = $('#hour2').val();
    min1 = $('#minute1').val();
    min2 = $('#minute2').val();

    sec1 = "00";
    if (min2 == "59") {
        sec2 = "59"
    } else {
        sec2 = "00"
    }
    hcomp1 = hour1 + '' + min1 + '' + sec1;
    hcomp2 = hour2 + '' + min2 + '' + sec2;

    seconds_start = (hour1 * 60 * 60) + (min1 * 60) + parseInt(sec1);
    seconds_end = (hour2 * 60 * 60) + (min2 * 60) + parseInt(sec2);

    $('#secondsstart').val(seconds_start);
    $('#secondsend').val(seconds_end);

    if (hcomp1 >= hcomp2) {
        popalert("warning", "Invalid Hour");
        return false;
    }

    if (fecha_check_e < fecha_check_s) {
        popalert("warning", "Invalid Date");
        return false;
    } else {
        document.forms[0].submit();
    }

    return false;
}

function licname(nx) {
    document.getElementById('licensename').innerHTML = nx;
}

function setdates(start, end) {
    var start_year = start.substr(0, 4);
    var start_month = start.substr(5, 2);
    var start_day = start.substr(8, 2);

    var end_year = end.substr(0, 4);
    var end_month = end.substr(5, 2);
    var end_day = end.substr(8, 2);

    dstart = MWJ_findSelect("day1"), mstart = MWJ_findSelect("month1"), ystart = MWJ_findSelect("year1");
    dend = MWJ_findSelect("day2"), mend = MWJ_findSelect("month2"), yend = MWJ_findSelect("year2");
    hour1 = MWJ_findSelect("hour1");
    minute1 = MWJ_findSelect("minute1");
    hour2 = MWJ_findSelect("hour2");
    minute2 = MWJ_findSelect("minute2");
    while (dstart.options.length) {
        dstart.options[0] = null;
    }
    while (dend.options.length) {
        dend.options[0] = null;
    }

    for (var x = 0; x < 31; x++) {
        dstart.options[x] = new Option(x + 1, x + 1);
    }
    for (var x = 0; x < 31; x++) {
        dend.options[x] = new Option(x + 1, x + 1);
    }

    x = start_day - 1;
    y = end_day - 1;
    dstart.options[x].selected = true;
    dend.options[y].selected = true;

    x = start_month - 1;
    y = end_month - 1;
    mstart.options[x].selected = true;
    mend.options[y].selected = true;

    for (var x = 0; x < ystart.options.length; x++) {
        if (ystart.options[x].value == '' + start_year + '') {
            ystart.options[x].selected = true;
            if (window.opera && document.importNode) {
                window.setTimeout('MWJ_findSelect( \'' + ystart.name + '\' ).options[' + x + '].selected = true;', 0);
            }
        }
    }
    for (var x = 0; x < yend.options.length; x++) {
        if (yend.options[x].value == '' + end_year + '') {
            yend.options[x].selected = true;
            if (window.opera && document.importNode) {
                window.setTimeout('MWJ_findSelect( \'' + yend.name + '\' ).options[' + x + '].selected = true;', 0);
            }
        }
    }

    $('#day1').trigger("chosen:updated");
    $('#day2').trigger("chosen:updated");
    $('#month1').trigger("chosen:updated");
    $('#month2').trigger("chosen:updated");
    $('#year1').trigger("chosen:updated");
    $('#year2').trigger("chosen:updated");
    $('#hour1').trigger("chosen:updated");
    $('#hour2').trigger("chosen:updated");
    $('#minute1').trigger("chosen:updated");
    $('#minute2').trigger("chosen:updated");


}

function pagupdate(urlin, objeto, pagina, parametros, element) {
    //debug(objeto);
    //debug(element);
    debug('pagupdate');
    var elemento = $(element).parent().parent().parent().parent().parent().parent().parent().parent();
    //debug(elemento);

    if (objeto.indexOf('detail') > -1) {
        elemento = $(element).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
    }

    var pars = "report=" + objeto + "&type=HTML&pagina=" + pagina;
    if (parametros != 'undefined') {
        pars += "&" + parametros;
    }
    $('#loader').show();

    $(elemento).fadeTo('fast', 0.01, function() {

        $.post(urlin, pars,

            function(data) {
                $(elemento).empty().append($(data)).fadeTo('fast', 1);
                $('#loader').hide();

                $(elemento).find('.helpleft').each(function(idx, el) {
                    if ($(el).attr('data-original-title') != 'undefined') {
                        $(el).attr('title', $(el).attr('data-original-title'));
                        $(el).attr('data-original-title', '');
                    }
                });
                $(elemento).find('.helpright').each(function(idx, el) {
                    if ($(el).attr('data-original-title') != 'undefined') {
                        $(el).attr('title', $(el).attr('data-original-title'));
                        $(el).attr('data-original-title', '');
                    }
                });
                $(elemento).find('.helptop').each(function(idx, el) {
                    if ($(el).attr('data-original-title') != 'undefined') {
                        $(el).attr('title', $(el).attr('data-original-title'));
                        $(el).attr('data-original-title', '');
                    }
                });
                $(elemento).find('.helpleft').unbind('hover').tipTip({
                    defaultPosition: 'left'
                });
                $(elemento).find('.helptop').unbind('hover').tipTip({
                    defaultPosition: 'top'
                });
                $(elemento).find('.helpright').unbind('hover').tipTip({
                    defaultPosition: 'right'
                });
            });
    });

    return false;
}

function ajupdate(pagina, objeto, parametros) {
    debug("ajupdate pagina " + pagina + " objeto " + objeto);
    elem = $('#' + objeto);
    debug(elem);
    //currentClass = elem.parent().parent().parent().parent().attr('class');
    //currentClass = currentClass + "xx";
    //debug('class '+currentClass);
    //elem.parent().parent().parent().parent().attr('class','col-md-12');
    //elem.parent().parent().parent().parent().addClass(currentClass);
    load = $('#loader');
    if (elem.css('display') == "none") {
        load.show();
        var url = pagina;
        var pars = parametros;
        $('#content' + objeto).load(pagina, parametros, function() {
            $('#loader').hide();
            $('#' + objeto).fadeIn();
        });

    } else {
        elem.fadeOut();
        load.css('display', 'none');
        //elem.parent().parent().parent().parent().removeClass('col-md-12');
        //currentClass = elem.parent().parent().parent().parent().attr('class');
        //currentClass = currentClass.substr(0,currentClass.indexOf('xx'),currentClass);
        //elem.parent().parent().parent().parent().addClass(currentClass);
        //debug(currentClass);
    }
}


function getHiddenSize(elem) {

    if (elem.css('display') != 'none') {
        return new Array(elem.outerWidth(), elem.outerHeight());
    } else {
        elem.css('display', 'none');
        return new Array(elem.outerWidth(), elem.outerHeight());
    }
}

function popalert(tipo, mensa) {
    //mensa = escape(mensa);
    jQuery.ajax({
        url: basehref + 'ajax-message.php',
        data: {
            'message': mensa,
            "tipo": tipo
        },
        dataType: 'script'
    });
    return false;
}

function debug(message) {
    if (window.console !== undefined) {
        console.log(message);
    }
}

function filter_clid(report) {

    filterReport = report;
    var jqi = $.prompt(idiom['Filter'] + ':  <input type=text class="form-control" id=filtertt name=filtertt value="' + $('#ftt').val() + '">', {
        buttons: {
            Ok: 1,
            Reset: 2
        }
    });
    jqi.bind('promptloaded', function(event, val, msg, fields) {
        $('#filtertt').focus();
    });
    jqi.bind('promptsubmit', function(event, val, msg, fields) {
        if (val == 1) {
            var mifilter = "filter=clid:" + $('#filtertt').val();
            $('#ftt').val($('#filtertt').val());
            pagupdate('export.php', filterReport, '1', mifilter);
        } else {
            $('#filtertt').val('');
            $('#ftt').val('');
            return false;
        }
    });
}

function export_submit(formid) {

    cual = "";
    report = "";

    $('#' + formid).find('input').each(function(idx, el) {
        if (el.name == "report") {
            report = el.value;
        }
    });

    $('#' + formid).find('button').each(function(idx, el) {
        if ($(el).attr('name') == "click") {
            cual = $(el).attr('data-alt');
            $(el).attr('name', '');
        }
    });

    if (cual == "pdf") {
        $('#' + formid).find('.type').each(function() {
            $(this).val('pdf');
        });
        return true;
    }
    if (cual == "csv") {
        $('#' + formid).find('.type').each(function() {
            $(this).val('csv');
        });
        return true;
    }
    //if(cual=="csv") { return true; }
    return false;
}

function msAddScript(url, pars) {
    eltScript = document.createElement("script");
    eltScript.setAttribute("type", "text/javascript");
    if (url.indexOf('?') > -1) {
        url += '&';
    } else {
        url += '?';
    }
    if (pars === undefined) {
        url += 'rand=' + Math.random();
    } else {
        url += 'rand=' + pars;
    }
    eltScript.setAttribute("src", url);
    document.getElementsByTagName('head')[0].appendChild(eltScript);
};

function wavPlay(file, iconid) {
    debug($('div#' + iconid));
    if ($('div#' + iconid).hasClass('playicon')) {
        debug("play " + file);
        window.TinyWav.Play('download.php?file=' + file, iconid);
    } else {
        debug("pause " + file);
        window.TinyWav.Pause(file, iconid);
    }
}

function wavplay(file, id, status) {
    $('#bplay').prop('disabled', true);
    $('#bpause').prop('disabled', true);
    wavesurfer.load(file);
    $('#audiofile').attr('href', file);
    $('#wavplayer').modal();

    wavesurfer.on('ready', function() {
        $('#bplay').prop('disabled', false);
        $('#bpause').prop('disabled', false);
        wavesurfer.play();
    });
}

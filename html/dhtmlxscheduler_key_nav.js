/*
@license
dhtmlxScheduler v.4.3.1 

This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
scheduler._temp_key_scope = function() {
    function e(e) {
        delete e.rec_type, delete e.rec_pattern, delete e.event_pid, delete e.event_length
    }
    scheduler.config.key_nav = !0;
    var t, a, i = null;
    scheduler.attachEvent("onMouseMove", function(e, i) {
        t = scheduler.getActionData(i).date, a = scheduler.getActionData(i).section
    }), scheduler._make_pasted_event = function(i) {
        var n = i.end_date - i.start_date,
            r = scheduler._lame_copy({}, i);
        if (e(r), r.start_date = new Date(t), r.end_date = new Date(r.start_date.valueOf() + n), a) {
            var l = scheduler._get_section_property(); 

            scheduler.config.multisection ? r[l] = i[l] : r[l] = a
        }
        return r
    }, scheduler._do_paste = function(e, t, a) { 
	//alert("san"); 
	//alert(t.toSource());
	//alert("keyboard");
        //scheduler.addEvent(t), scheduler.callEvent("onEventPasted", [e, t, a])
	t.input_type="";
	scheduler.addEventNow(t); 
    }, scheduler._is_key_nav_active = function() {
        return this._is_initialized() && !this._is_lightbox_open() && this.config.key_nav ? !0 : !1
    }, dhtmlxEvent(document, _isOpera ? "keypress" : "keydown", function(e) {
        if (!scheduler._is_key_nav_active()) return !0;
	
        if (e = e || event, 37 == e.keyCode || 39 == e.keyCode) {
            e.cancelBubble = !0;
            var t = scheduler.date.add(scheduler._date, 37 == e.keyCode ? -1 : 1, scheduler._mode);

            return scheduler.setCurrentView(t), !0
        }
        var a = scheduler._select_id; 
	if(e.altKey && 73 == e.keyCode)
	{
		var obj = scheduler.getEvent(a); //alert(a);
		
		var frame = obj.eframe;
		/*if(obj.eframe<25)
		{
			frame = parseInt(obj.eframe);
			alert(frame);
		}
		if(obj.eframe==24)
		{
			frame = 0;
		}*/		

		scheduler.addEventNow({
    			start_date: new Date(obj.end_date),
    			end_date:   new Date(obj.end_date),
    			input_type: "1",
			sframe: frame.toString(),
			description:a.toString()

		}); 
	}
	if(e.altKey && 80 == e.keyCode)
	{
		var obj = scheduler.getEvent(a); //alert(a);
		var frame = obj.eframe;
 		var n = scheduler.getEvent(scheduler._buffer_id);
            	if (n) { 
                var r = scheduler._make_pasted_event(n); //alert(r.toSource());
		r.input_type="1";
		r.id = scheduler.uid();
    		r.start_date=obj.end_date; //alert(r.start_date);
		r.sframe = frame.toString();
		var total_Sec = ((obj.end_date).getTime()+parseInt(r.duration1))/1000; //alert(r.duration1);
		var frameSec = 0;
		var frame1 = 0;
		var eventEndFrame=parseInt(frame)+parseInt(r.dframe);
		if(eventEndFrame>24)
		{
			frame1 =eventEndFrame%25;
			var dframe1=eventEndFrame-frame1;
			frameSec=(dframe1/25);	
		}
		else
		{
			frame1=eventEndFrame;
		}
		total_Sec = (total_Sec + frameSec)*1000; 
		r.end_date = new Date(total_Sec);				
		r.eframe =  frame1; 
    		r.description=a.toString(); //alert(r.toSource());
		scheduler.addEventNow(r); 
                /*if (i) r.id = scheduler.uid(), scheduler._do_paste(i, r, n);*/
		}
	}
        if (e.ctrlKey && 67 == e.keyCode) return a && (scheduler._buffer_id = a, i = !0, scheduler.callEvent("onEventCopied", [scheduler.getEvent(a)])), !0;
        if (e.ctrlKey && 88 == e.keyCode && a) { //alert("Copy-1");
            i = !1, scheduler._buffer_id = a;
            var n = scheduler.getEvent(a);
            scheduler.updateEvent(n.id), scheduler.callEvent("onEventCut", [n])
        }
        if (e.ctrlKey && 86 == e.keyCode) { 
            var n = scheduler.getEvent(scheduler._buffer_id);
            if (n) { //alert("Copy 2");
                var r = scheduler._make_pasted_event(n);
                if (i) r.id = scheduler.uid(), scheduler._do_paste(i, r, n);
                else {
                    var l = scheduler.callEvent("onBeforeEventChanged", [r, e, !1, n]);
                    l && (scheduler._do_paste(i, r, n), i = !0)
                }
            }
            return !0
        }
    })
}, scheduler._temp_key_scope();
//# sourceMappingURL=../sources/ext/dhtmlxscheduler_key_nav.js.map

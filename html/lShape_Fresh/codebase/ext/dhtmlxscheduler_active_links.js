/*
@license
dhtmlxScheduler v.4.3.1 

This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
scheduler.config.active_link_view="day",scheduler._active_link_click=function(e){var t=e.target||event.srcElement,i=t.getAttribute("jump_to"),s=scheduler.date.str_to_date(scheduler.config.api_date);return i?(scheduler.setCurrentView(s(i),scheduler.config.active_link_view),e&&e.preventDefault&&e.preventDefault(),!1):void 0},scheduler.attachEvent("onTemplatesReady",function(){var e=function(e,t){t=t||e+"_scale_date",scheduler.templates["_active_links_old_"+t]||(scheduler.templates["_active_links_old_"+t]=scheduler.templates[t]);

var i=scheduler.templates["_active_links_old_"+t],s=scheduler.date.date_to_str(scheduler.config.api_date);scheduler.templates[t]=function(e){return"<a jump_to='"+s(e)+"' href='#'>"+i(e)+"</a>"}};if(e("week"),e("","month_day"),this.matrix)for(var t in this.matrix)e(t);this._detachDomEvent(this._obj,"click",scheduler._active_link_click),dhtmlxEvent(this._obj,"click",scheduler._active_link_click)});
//# sourceMappingURL=../sources/ext/dhtmlxscheduler_active_links.js.map
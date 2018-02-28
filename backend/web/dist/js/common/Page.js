/**
 * Created by xiaoda on 2017/3/2.
 */

var Page = Class.extend({
    ctor: function (perPage, pageArray) {
        this.initView(perPage, pageArray);
        this.initEvent();
    },

    initView: function (perPage, pageArray) {
        $("#data_table_paginate").before(this.createPerPage(perPage, pageArray));
        this.per_page = $("strong#per_page");
        this.page_menu = $(".page_menu");
        this.page_icon = $(".page_icon");
        this.page_list = $(".page_list");
        this.page_info = $("#page_info");

        this.widgetPage = $("#data_table_paginate");

        this.per_page.html(perPage ? per_page:50);
    },

    initEvent: function () {
        this.page_menu.click(this.showPage.bind(this));
        this.page_icon.click(this.showPage.bind(this));
    },

    changePerPage: function (page) {
        Event.dispatch(Event.PAGE_CHANGE, page);
    },

    createPerPage: function (perPage, pageArray) {
        var defaultPageArray = [5,10,15,20,25,30,35,40,45,50,100,200,250,500,1000];
        this.pageArray = pageArray ? pageArray : defaultPageArray;
        perPage = perPage ? perPage : 50;
        var content="";
        content += '<div id="pageinfo" class="pageinfo">';
        content += $.format('<a class="page_menu" id="page_menu" >{0}<strong id="per_page">{1}</strong>{2}</a>', "每页", perPage, "条");
        content += '<span class="page_icon"></span>';
        content += '<ul class="page_list" >';
        for(var i = 0; i < this.pageArray.length; ++i) {
            var numValue = this.pageArray[i];
            // if(numValue == perPage) {
            //     content += $.format("<li id='page_list_{0}' class='page_active' ><a>{1}</a></li> ", i, numValue);
            // } else {
                content += $.format("<li id='page_list_{0}'><a onclick='Page.changePerPage({1});'>{2}</a></li>", i, numValue, numValue);
            // }
        }
        content += '</ul>';
        content += '</div>';
        return content;
    },

    showPage: function(event) {
        var status = this.page_list.css("display");
        if(status == "none") {
            this.page_list.css("display","block");
            event.stopPropagation();
            $(document).one("click",function() {
                this.page_list.css("display","none");
                event.stopPropagation();
            }.bind(this))
        } else {
            this.page_list.css("display","none");
        }
    },

    setPageSize: function(page) {
        var oldPage = this.per_page.html();
        this.per_page.html(page);
        for(var i = 0; i < this.pageArray.length; ++i) {
            if (this.pageArray[i] == oldPage) {
                $("#page_list_"+i).removeClass('page_active');
            }
            if (this.pageArray[i] == page) {
                $("#page_list_"+i).addClass('page_active');
            }
        }
        var lastPage = page * (this.currentPage+1);
        this.page_info.html($.format('从{0}到{1}共{2}条记录', page*this.currentPage+1, this.totleCount>lastPage ? lastPage:this.totleCount, this.totleCount));

        //this.pagewidget.html($.format(""));
    },

    getPageSize: function() {
        return this.per_page.html();
    },

    setCurrentPage: function(page) {
        this.currentPage = page;
    },

    getCurrentPage: function() {
        return this.currentPage;
    },

    setTotleCount: function(count) {
        this.totleCount = count;
    },
    setPageWidget:function(widgetpage){
        this.widgetPage.html(widgetpage);
    }

});
$(function() {
    Page = new Page();
});

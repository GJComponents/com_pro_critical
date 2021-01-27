/***********************************************************************************************************************
 * ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗  ╔╗╔╗╔╗ ╔═══╗ ╔══╗   ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
 * ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝  ║║║║║║ ║╔══╝ ║╔╗║   ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
 * ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗  ║║║║║║ ║╚══╗ ║╚╝╚╗  ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
 * ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║  ║║║║║║ ║╔══╝ ║╔═╗║  ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
 * ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║  ║╚╝╚╝║ ║╚══╗ ║╚═╝║  ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
 * ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝  ╚═╝╚═╝ ╚═══╝ ╚═══╝  ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
 *----------------------------------------------------------------------------------------------------------------------
 * @author Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
 * @date 25.11.2020 19:04
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 **********************************************************************************************************************/
/* global jQuery , Joomla   */
window.html_task_setUpTask = function () {
    var $ = jQuery;
    var self = this;
    // Домен сайта
    var host = Joomla.getOptions('GNZ11').Ajax.siteUrl;
    // Медиа версия
    var __v = '';

    this.__type = false;
    this.__plugin = false;
    this.__name = false;
    this._params = {};
    // Параметры Ajax по умолчвнию
    this.AjaxDefaultData = {
        option: 'com_pro_critical',
        view: 'html_task',
        format: 'json',
        raw: true,
        task: 'html_task.getFormSetUpTask',
    };
    // Default object parameters
    this.ParamsDefaultData = {
        // Медиа версия
        __v: '1.0.0',
        // Режим разработки
        development_on: false,
    }

    /**
     * Start Init
     * @constructor
     */
    this.Init = function () {
        this._params = Joomla.getOptions('setUpTask', this.ParamsDefaultData);
        __v = self._params.development_on ? '' : '?v=' + self._params.__v;
        // Параметры Ajax Default
        this.setAjaxDefaultData();
        // Добавить слушателей событий
        this.addEvtListener();

        console.log(this._params)
        console.log(this.AjaxDefaultData);
    }
    /**
     * Добавить слушателей событий
     */
    this.addEvtListener = function () {

    };
    /**
     * загрузить форму настрийки для текущего задания
     */
    this.loadSetting = function (){
        self.processing =  this.getTask();
        if (!self.processing ) return  ;

        var Data = {
            html_processing : self.processing,
            task_data : $('#jform_task_data').val(),
        }
        this.AjaxPost(Data).then(function (r){
            self.prepareShowon( r.data.html )
            self.renderModalForm( )
        },function (err){console.log(err)});
    };
    this.saveData = function (event){
        var $form = $('#processingForm')
        var dataOb = self.Form.getFormDataToJson($form);
        var dataJson = JSON.stringify( dataOb )
        $('#adminForm').find('#jform_task_data').val(dataJson)
        self.ModalWindowForm.close();
    }

    /**
     * Подготовить форму перед открытием в модальном окне
     * @param form
     */
    this.prepareShowon = function (form){
        $('body').append($('<div />',{
            id : 'processingFormTemp',
            style : 'display: none;' ,
            html : form ,
        }));

        self.SHOWON.Init();

        /**
         * Инициялизация подскизок
         */
        initPopovers();
        function initPopovers (event, container) {
            $(container || document).find(".hasPopover")
                .popover({"html": true,"trigger": "hover focus","container": "body"});
        }

    };

    this.ModalWindowForm;
    /**
     * отобразить форму в модальном окне
     */
    this.renderModalForm = function (){
        var form = $('#processingFormTemp');
        self.__loadModul.Fancybox().then(function (a) {
            self.ModalWindowForm = a ;
            self.ModalWindowForm.open( form , {
                // src : form ,
                baseClass : 'processingForm '+self.processing ,
                touch : false ,
                afterLoad: function(instance, current ){
                    // Програмное добавление в content елемента
                    // current.$content.append('<a data-fancybox-close class="button-close" href="javascript:;">afterLoad</a>');
                },
                beforeShow   : function(instance, current)   {
                    this.title = 'TITLE';
                    console.log( instance )
                    console.log( current )
                },
                afterShow   : function(instance, current)   {
                    self.SHOWON.Init();
                },
                helpers: { title : { type : "inside" } },
                baseTpl:
                    '<div class = "fancybox-container" role = "dialog" tabindex = "- 1">' +
                        '<div class = "fancybox-bg"> </div>' +
                        '<div class = "fancybox-inner">' +
                            '<div class = "fancybox-infobar"> ' +
                                '<span data-fancybox-index> </span> ' +
                                '& nbsp; / & nbsp; ' +
                                '<span data-fancybox-count> </span> ' +
                            '</div>' +
                            '<div class = "fancybox-toolbar"> {{buttons}} </div>' +
                            '<div class = "fancybox-navigation"> {{стрелки}} </div>' +
                            '<div class = "fancybox-stage"> </div>' +
                            '<div class = "fancybox-caption"> ' +
                            '   <div class = "fancybox-caption__body iiiiii">ffff </div> ' +
                            '</div>' +
                        '</div>' +
                    '</div>',
            })
        });
    }
    /**
     * Получить текущие задание
     * @returns {boolean|jQuery|string|undefined}
     */
    this.getTask = function (){
        var selector = '#jform_html_processing' ;
        var T = $('#jform_html_processing').val();
        if (!T) {
            alert('Выбирете операцию задачи');
            return false ;
        }
        return T ;
    };

    /**
     * Отправить запрос
     * @param Data - отправляемые данные
     * Должен содержать Data.task = 'taskName';
     * @returns {Promise}
     * @constructor
     */
    this.AjaxPost = function (Data) {
        var data = $.extend(true, this.AjaxDefaultData, Data);
        return new Promise(function (resolve, reject) {
            self.getModul("Ajax").then(function (Ajax) {

                // Не обрабатывать сообщения
                Ajax.ReturnRespond = true;
                // Отправить запрос
                Ajax.send(data, self._params.__name).then(function (r) {
                    resolve(r);
                }, function (err) {
                    console.error(err);
                    reject(err);
                })
            });
        });
    };
    /**
     * Параметры Ajax Default
     */
    this.setAjaxDefaultData = function () {
        this.AjaxDefaultData.group = this._params.__type
        this.AjaxDefaultData.plugin = this._params.__name
    }
    /**
     * Обект работы с формами
     * @type {{serialize: (function(*=): *), getFormDataToJson: (function(*): {})}}
     */
    this.Form = {
        /**
         * Данные формы в Json
         * @param $form
         * @returns {{}}
         */
        getFormDataToJson : function ($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                var name = n['name'] ;

                name = self.TEXT.getBetween(name ,'[' , ']' );
                indexed_array[ name ] = n['value'];
            });
            return indexed_array;
        },
        /**
         * Serializes - форм || элементов форм не вложенных в тег <form>
         * Serializes form or any other element with jQuery.serialize
         * @param el - <form> OR <div>
         */
        serialize : function(el) {
            var serialized = $(el).serialize();
            if (!serialized) // not a form
                serialized = $(el).find('input[name],select[name],textarea[name]').serialize();
            return serialized;
        }
    };
    this.Init();
};

window.html_task_setUpTask.prototype = new GNZ11();
window.setUpTask = new window.html_task_setUpTask();

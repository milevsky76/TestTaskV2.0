//Объявили анонимную функцию, и вызвали ее, передав параметром объект jQuery
!(function($){
    //Используем cтрогий режим
    'use strict';

    var script = {};
    
    
    script.ajax = {
        errorMessage: translate("error:ajax-request"),

        ajaxSettings:{
            $loaderEl: $('#loader'),

            beforeSend: function(){
                this.$loaderEl.show();
            },

            complete: function(){
                this.$loaderEl.hide();
            }
        },

        init: function(){
            $.ajaxSetup(this.ajaxSettings);
        },
        //Возврат по action
        callbacks:{
            login: function ($form, data){
                if (data.status === 'ok'){
                    if (data.data && data.data.redirect){
                        window.location.href = data.data.redirect;
                    }
                }
            },

            logout: function ($form, data){
                if (data.status === 'ok'){
                    if (data.data && data.data.redirect){
                        window.location.href = data.data.redirect;
                    }
                }
            },

            register: function ($form, data){
                if (data.status === 'ok'){
                    if (data.data && data.data.redirect){
                        window.location.href = data.data.redirect;
                    }
                }
            }

        }
    };

    script.ajaxform = {
        $forms: null,

        init: function(selector){
            this.$forms = $(selector);
            this.$forms.attr('novalidate', 'novalidate');
            this.initHandler();
        },

        initHandler: function(){
            this.$forms.submit(function(){
                var $_this = $(this);
                var result = script.ajaxform.validate($_this);

                if (result === true){
                    script.ajaxform.go($_this);
                }

                return false;
            });
        },
        //Получение action
        callback: function($form, data) {
            var action = getURLParam('act', $form.attr('action'));

            if (!action || action === ';'){
                action = $form.find('[name|="act"]').val();
            }

            if (action !== ';' && action.length){
                var callbackFunction = script.ajax.callbacks[action];
                if(typeof callbackFunction === 'function') {
                    callbackFunction.call(this, $form, data);
                }
            }                            
        },
        //Проверка на валидность
        validate: function($form){
            var $fields = $form.find(':input'), isValid = true, $e;
                
            let re, $spanLog = $form.find("span").empty();           
            
            $fields.each(function(i, e) {
                $(e).removeClass('error-message');
            });

            $form.find('.error-message').remove();
            
            $fields.each(function(i, e) {
                if(e.hasAttribute("required")) {
                    $e = $(e);
                    if (!$e.val().trim()) {
                        $e.addClass('error-message').focus();
                        isValid = false;
                        return isValid;
                    }
                }
            });

            //Логин
            let userlogin = $form.find("[name=userlogin]");              
            if(userlogin.length != 0){
                re = new RegExp("[А-Яа-яёЁA-Za-z0-9]{6,}")
                $spanLog = $form.find('span[data-field|=userlogin]');
                
                if(userlogin.val() == ""){
                    return true;
                }else{
                    if(userlogin[0].value.length < 6){
                        $spanLog.html("Минимум 6 символов").show();
                        return false;
                    }else if(!re.test(userlogin.val())){
                        $spanLog.html("Только буквы и цифры").show();
                        return false;
                    }
                }
            }
         
            //Пароль
            let password = $form.find("[name=password]");
            if(password.length != 0){
                re = new RegExp("(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[А-Яа-яёЁA-Za-z0-9!@#$%^&*]{6,}")
                $spanLog = $form.find('span[data-field|=password]');
                
                if(password.val() == ""){
                    return true;
                }else{
                    if(password[0].value.length < 6){
                        $spanLog.html("Минимум 6 символов").show();
                        return false;
                    }else if(!re.test(password.val())){
                        $spanLog.html("Пароль должен содержать цифру, буквы в разных регистрах и спец символ").show();
                        return false;
                    }
                }
            }
            
            //Email
            let useremail = $form.find("[name=useremail]");
            if(useremail.length != 0){
                re = new RegExp(".+@.+\..+")
                $spanLog = $form.find('span[data-field|=useremail]');
                
                if(useremail.val() == ""){
                    return true;
                }else{
                    if(useremail[0].value.length < 6){
                        $spanLog.html("Минимум 6 символов, шаблон: example@example.example").show();
                        return false;
                    }else if(!re.test(useremail.val())){
                        $spanLog.html("Шаблон: example@example.example").show();
                        return false;
                    }
                }
            }
            
            //name
            let username = $form.find("[name=username]");
            if(username.length != 0){
                re = new RegExp("[А-Яа-яёЁA-Za-z0-9]{2}")
                $spanLog = $form.find('span[data-field|=username]');
                
                if(username.val() == ""){
                    return true;
                }else{
                    if(username[0].value.length != 2){
                        $spanLog.html("Только 2 символа").show();
                        return false;
                    }else if(!re.test(username.val())){
                        $spanLog.html("Только буквы и цифры").show();
                        return false;
                    }
                }
            }
    
            return isValid;
        },
        go: function($form){
            var method = $form.attr('method') || 'GET';
            var action = $form.attr('action') || '.';

            var ajaxSettings = {
                type: method,
                url: action,
                data: $form.serialize()
            };

            var $formInputs = $form.find('input,textarea,select');
            $formInputs.attr('readonly', 'readonly');

            var $formButtons = $form.find(':button,input[type="submit"]');

            $formButtons.attr("disabled", "disabled");

            ajaxSettings.complete = function(){
                script.ajax.ajaxSettings.$loaderEl.hide();
                $formInputs.removeAttr('readonly');
                $formButtons.removeAttr('disabled');
            };

            ajaxSettings.success = function(response){
                var data;
                if (typeof response === 'object'){
                    data = response;
                }else{
                    try{
                        data = JSON.parse(response);
                    }catch (e){
                        window.alert(translate("error:form-submit"));
                        return;
                    }
                }
                script.ajaxform.validateByAjax($form, data);
            };

            $.ajax(ajaxSettings);
        },
        //Валидация ответа ajax
        validateByAjax: function($form, data){
            if (data.status === 'ok') {                
                if (data.message !== undefined && data.message !== null){
                }
                if(data.redirect === true){
                    if (data.url !== undefined && data.url !== null){
                        window.location.href = data.url;
                    }else{
                        window.location.reload();
                    }
                }
            }else if (data.status === 'Error'){
                let $spanLog = $form.find("span").empty();
                /*let $mainErrorContainer = $form.find('.main-error');*/
                if (data.code === 'main'){
                    /*if ($mainErrorContainer !== null) {
                        $mainErrorContainer.html('<p class="error">' + data.message + '</p>');
                    } else {
                        $form.append('<p class="error">' + data.message + '</p>');
                    }*/
                } else {
                    $spanLog = $form.find('span[data-field|="' + data.code + '"]');
                    $spanLog.html(data.message).show();
                    
                    /*let $errField = $form.find('[name|="' + data.code + '"]');
                    $mainErrorContainer.html(data.message).show();
                    $errField.focus();*/
                }
            }

            this.callback($form, data);
        }
    };
    
    //Получение URL параметра
    function getURLParam(name, url){
        url = url || window.location.href;
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)",
            regex = new RegExp(regexS),
            results = regex.exec(url);
        if (results === undefined || results === null){
            return ';';
        }else{
            return decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    }
    
    //Перевод cообщения об ошибках и уведомления
    function translate(params, lang){
        var messages = {}, translated = "", code;

        messages.ru_RU = {
            "error":{
                "form-submit": "Произошла ошибка при отправке формы",
                "ajax-request": "Произошла ошибка при отправке запроса",
            },
            "notice":{
                "confirm": "Подтвердите действие",
            }
        };
        
        lang = lang || "ru_RU";
        
        params = params.toLowerCase().split(':');
        
        if(messages[lang] !== undefined && params.length){
            for(var i = 0, msgcat = messages[lang]; i < params.length; i++){
                code = params[i];
                if(typeof msgcat[code] === 'object'){
                    msgcat = msgcat[code];
                }
                if(typeof msgcat[code] === 'string'){
                    translated = msgcat[code];
                    break;
                }
            }
        }
        
        return translated;
    }

    script.init = function(){
        this.ajaxform.init("form.ajax");
        this.ajax.init();
    };

    $(document).ready(function($){
        script.init();
    });
})(jQuery);
const app = (function () {

    let url;

    const init = function () {
        console.log(`
 #####                                                     
#       #    # #####  #####  ####   ####      #####  ##### 
#       #    # #    #   #   #    # #          #    #   #   
#       #    # #    #   #   #    #  ####      #    #   #   
#       #    # #####    #   #    #      #     #####    #   
#       #    # #   #    #   #    # #    #     #        #   
 #####   ####  #    #   #    ####   ####   #  #        #    
                                                           `);
        url = window.location.origin;
        $('[data-toggle="tooltip"], [rel="tooltip"]').tooltip();
    };

    const validateUrl = function (str) {
        const pattern = new RegExp('^(https:\\/\\/)' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
        return !!pattern.test(str);
    };

    // Link shorter
    const short = function () {
        const target = $("#target").val();

        if (app.validateUrl(target)) {
            $.ajax({
                type: "POST",
                method: "POST",
                url: url + "/link/short",
                async: true,
                data: {target: target},
                success: function (data) {
                    $("#target").val(data);

                    if ($("#copy").hasClass("d-none")) {
                        $("#copy").toggleClass('d-none');
                    }
                },
                error: function (data) {
                    app.notify("Erro a criar link", "danger");
                    console.log(data);
                }
            });
        } else {
            app.notifyError();
        }
    };

    // copy
    const copy = function () {
        const copyText = $("#target");
        copyText.select();
//        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        setTimeout(function () {
            $("#target").val('');
        }, 500);

        $("#copy").toggleClass('d-none');
        app.notify("Copiado com sucesso!", "success", 100);
    };

    // copy key
    const copyKey = function () {
        const copyText = $("#api-key");
        copyText.select();
        document.execCommand("copy");

        app.notify("Copiada com sucesso!", "success", 100);
    };

    // Notify Actions
    const notify = function (message, type, time) {
        $.notify({
            icon: 'fa fa-bell',
            message: message
        }, {
            type: type,
            timer: time
        });
    };

    // Notify error
    const notifyError = function () {
        $("#target-div").toggleClass('has-error');
        $(".help-block").toggleClass('visible-off');
        setTimeout(function () {
            $("#target-div").toggleClass('has-error');
            $(".help-block").toggleClass('visible-off');
        }, 2000);
    };

    // Return the public facing methods for the App
    return {
        init: init,
        short: short,
        copy: copy,
        copyKey: copyKey,
        validateUrl: validateUrl,
        notify: notify,
        notifyError: notifyError
    };

}());

$(function () {
    app.init();
});

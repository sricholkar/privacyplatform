

var Profile = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (Profile.check("name") == false) {
            return false;
        }
        if (Profile.check("email") == false) {
            return false;
        }
        $("#profileForm")[0].submit();
    }
};

var SignUp = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("name") == false) {
            return false;
        }
        if (SignUp.check("username") == false) {
            return false;
        }
        if (SignUp.check("email") == false) {
            return false;
        }
        if (SignUp.check("password") == false) {
            return false;
        }
        if ($("#password")[0].value != $("#repeatPassword")[0].value) {
            $("#repeatPassword")[0].focus();
            $("#repeatPassword_alert").show();

            return false;
        }
        $("#registerForm")[0].submit();
    }
}

        $(document).ready(function () {
            $("#registerForm .alert").hide();
            $("div.profile .alert").hide();
        });

        var gadgets = document.getElementsByClassName("recommendator");
        for (var i=gadgets.length; i--;) {
            gadgets[i].addEventListener('click', myFunc, false);
        }
        function myFunc() {
            document.getElementsByClassName("loop1")[0].innerHTML = this.innerHTML + ":";
            document.getElementsByClassName("survey")[0].setAttribute("href",
                "../"+(this.innerHTML).toLowerCase().replace(/\s+/g, '') + "/continue/De");
        }

function warning() {
    var x = document.profileForm.name;
    var y = document.profileForm.email;
    if (x.value == "") {
        document.getElementById('name_alert').style.display = "block";
    } else if (y.value == ""){
        document.getElementById('email_alert').style.display = "block";
    }else {
        document.getElementById('name_alert').style.display = "none";
        document.getElementById('email_alert').style.display = "none";
    }
}


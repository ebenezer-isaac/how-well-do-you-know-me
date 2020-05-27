function change() {
    $("#signreq").fadeOut(500);
    setTimeout(function () {
        $("#loader").fadeIn(500);
    }, 500);
    setTimeout(function () {
        $("#loader").fadeOut(500);
    }, 2500);
    setTimeout(function () {
        $("#content").fadeIn(500);
    }, 4000);
}
function login() {
    $("#content").fadeOut(500);
    setTimeout(function () {
        $("#loader").fadeIn(500);
        var user = document.getElementById("username").value;
        var pass = document.getElementById("password").value;
        var reqTimeout = setTimeout(function ()
        {
            alert("Request timed out.");
        }, 5000);
        try {
            var xhr = $.ajax({
                type: "GET",
                url: "checker.php?name=" + user + "",
                dataType: "text",
                success: function (data) {
                    if (data.search("Hurray") === -1)
                    {
                        $("#loader").fadeOut(500);
                        setTimeout(function () {
                            $("#content").fadeIn(500);
                        }, 500);

                        setTimeout(function () {
                            $("#invalid").fadeIn(250);
                        }, 1000);
                        setTimeout(function () {
                            $("#invalid").fadeOut(250);
                        }, 2500);
                    } else {
                        $("#loader").fadeOut(500);
                        var xyz = $.ajax({
                            type: "GET",
                            url: "writer.php?name=" + user + "&pass=" + pass,
                            dataType: "text",
                            success: function () {
                            },
                            error: function () {
                            },
                            complete: function () {
                                clearTimeout(reqTimeout);
                            }
                        });
                        setTimeout(function () {
                            $("#quiz").fadeIn(500);
                        }, 500);
                    }
                },
                error: function () {
                    $("#loader").fadeOut(500);
                    setTimeout(function () {
                        $("#content").fadeIn(500);
                    }, 500);

                    setTimeout(function () {
                        $("#invalid").fadeIn(250);
                    }, 1000);
                    setTimeout(function () {
                        $("#invalid").fadeOut(250);
                    }, 2500);
                },
                complete: function () {
                    clearTimeout(reqTimeout);
                }
            });
        } catch (err) {
            console.log(err);
        }
    }, 500);
}
function check() {
    var user = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (user.length >= 4 && password.length >= 8)
    {
        document.getElementById("login-btn").disabled = false;
    } else
    {
        document.getElementById("login-btn").disabled = true;
    }
}
